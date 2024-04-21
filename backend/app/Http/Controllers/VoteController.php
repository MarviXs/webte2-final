<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\QuestionClosure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function show_question(string $code)
    {
        $question = Question::where('code', $code)->firstOrFail();
        return new QuestionResource($question);
    }

    public function answer(Request $request, string $code)
    {
        $question = Question::where('code', $code)->firstOrFail();

        // For scramble docs
        $request->validate([
            'choice_id' => 'nullable',
            'open_answer' => 'nullable',
        ]);

        if ($question->question_type === 'choice') {
            $validated = $request->validate([
                'choice_id' => 'required'
            ]);
            $question->choices()->findOrFail($validated['choice_id']);
        } else {
            $validated = $request->validate([
                'open_answer' => 'required',
            ]);
        }

        $question->answers()->create([
            'choice_id' => $question->question_type === 'choice' ? $validated['choice_id'] : null,
            'open_answer' => $question->question_type === 'open' ? $validated['open_answer'] : null,
        ]);

        return response()->json(['message' => 'Answer submitted successfully.']);
    }


    /**
     * @response array{ "answer": "Yes", "count": 2 }
     */
    public function result(string $code)
    {
        $question = Question::with('choices', 'answers', 'answers.choice')->where('code', $code)->firstOrFail();
        $latest_closure = $question->closures()->latest('created_at')->first();

        $startTime = $latest_closure?->created_at;

        $query = $this->prepareResultQuery($question, $startTime);
        $results = $query->get()->toArray();

        return response()->json($results);
    }

    /**
     * @response array{ "answer": "Yes", "count": 2 }
     */
    public function result_archive(string $question_id, $closure_id)
    {
        $question = Auth::user()->questions()->with('choices', 'answers', 'answers.choice')->findOrFail($question_id);
        $closure = $question->closures()->findOrFail($closure_id);
        $older_closure = $question->closures()->where('created_at', '<', $closure->created_at)->latest('created_at')->first();

        $startTime = $older_closure ? $older_closure->created_at : null;
        $endTime = $closure->created_at;

        $query = $this->prepareResultQuery($question, $startTime, $endTime);
        $results = $query->get()->toArray();

        return response()->json($results);
    }

    private function prepareResultQuery(Question $question, $startTime = null, $endTime = null)
    {
        $is_open = $question->question_type === 'open';
        $query = $question->answers()
            ->when(!$is_open, function ($query) {
                return $query->join('choices', 'answers.choice_id', '=', 'choices.id')
                    ->select('choices.choice_text as answer');
            }, function ($query) {
                return $query->select('open_answer as answer');
            })
            ->selectRaw('count(*) as count')
            ->when($startTime, fn($query) => $query->where('answers.created_at', '>', $startTime))
            ->when($endTime, fn($query) => $query->where('answers.created_at', '<=', $endTime))
            ->groupBy($is_open ? 'open_answer' : 'choices.choice_text');

        return $query;
    }

}
