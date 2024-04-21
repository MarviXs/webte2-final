<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\VoteClosureResource;
use App\Models\Question;
use App\Models\VoteClosure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function close(Request $request, string $question_id)
    {
        $validated = $request->validate([
            'note' => 'nullable'
        ]);

        $question = Auth::user()->questions()->findOrFail($question_id);

        $vote_closure = VoteClosure::create([
            'question_id' => $question->id,
            'note' => $validated['note']
        ]);

        return new VoteClosureResource($vote_closure);
    }

    public function closures(Request $request, string $question_id)
    {
        $question = Auth::user()->questions()->findOrFail($question_id);
        return VoteClosureResource::collection($question->closures);
    }


    /**
     * @response array{ "results": array{ "answer": "Yes", "count": 2 } }
     */
    public function result(string $code)
    {
        $question = Question::with('choices', 'answers', 'answers.choice')->where('code', $code)->firstOrFail();
        $latest_closure = $question->closures()->latest('created_at')->first();

        $startTime = $latest_closure?->created_at;

        $query = $this->prepareResultQuery($question, $startTime);
        $results = $query->get()->toArray();

        $response = [
            'results' => $results,
        ];

        return response()->json($response);
    }


    /**
     * @response array{ "closure": VoteClosureResource, "results": array{ "answer": "Yes", "count": 2 } }
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

        $response = [
            'closure' => new VoteClosureResource($closure),
            'results' => $results,
        ];

        return response()->json($response);
    }


    /**
     * @response array{ "closure": VoteClosureResource, "results": array{ "answer": "Yes", "count": 2 } }[]
     */
    public function result_compare(string $question_id)
    {
        $question = Auth::user()->questions()->with('choices', 'answers', 'answers.choice')->findOrFail($question_id);
        $closures = $question->closures()->orderBy('created_at')->get();

        $comparisons = [];

        $previousClosure = null;
        foreach ($closures as $closure) {
            $startTime = $previousClosure ? $previousClosure->created_at : null;
            $endTime = $closure->created_at;
            $previousClosure = $closure;

            $query = $this->prepareResultQuery($question, $startTime, $endTime);
            $results = $query->get()->toArray();

            $comparisons[] = [
                'closure' => new VoteClosureResource($closure),
                'results' => $results,
            ];
        }

        // Add current voting data
        $latestClosure = $question->closures()->latest('created_at')->first();
        $startTime = $latestClosure ? $latestClosure->created_at : null;
        $currentResults = $this->prepareResultQuery($question, $startTime, now())->get();
        $comparisons[] = [
            'closure' => null,
            'results' => $currentResults,
        ];

        $comparisons = array_reverse($comparisons);

        return response()->json($comparisons);
    }


    private function prepareResultQuery(Question $question, $startTime = null, $endTime = null)
    {
        if ($question->question_type === 'choice') {
            $query = $question->choices()
                ->leftJoin('answers', function ($join) use ($question, $startTime, $endTime) {
                    $join->on('answers.choice_id', '=', 'choices.id')
                        ->where('answers.question_id', '=', $question->id);
                    if ($startTime) {
                        $join->where('answers.created_at', '>', $startTime);
                    }
                    if ($endTime) {
                        $join->where('answers.created_at', '<=', $endTime);
                    }
                })
                ->select('choices.choice_text as answer')
                ->selectRaw('COUNT(answers.id) as count')
                ->groupBy('choices.id');
        } else {
            $query = $question->answers()
                ->select('open_answer as answer')
                ->selectRaw('COUNT(*) as count')
                ->when($startTime, fn($query) => $query->where('answers.created_at', '>', $startTime))
                ->when($endTime, fn($query) => $query->where('answers.created_at', '<=', $endTime))
                ->groupBy('open_answer');
        }

        return $query;
    }

}
