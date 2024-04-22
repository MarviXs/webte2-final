<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\VoteClosureResource;
use App\Models\Question;
use App\Models\VoteClosure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class VoteController extends Controller
{
    /**
     *  Get question using code
     */
    public function show_question(string $code)
    {
        $question = Question::where('code', $code)->firstOrFail();
        return new QuestionResource($question);
    }

    /**
     *  Answer a question
     */
    public function answer(Request $request, string $code)
    {
        $question = Question::where('code', $code)->firstOrFail();

        // For scramble docs
        $request->validate([
            'choices' => 'nullable',
            'open_answer' => 'nullable',
        ]);

        if ($question->question_type === 'multiple_choice' || $question->question_type === 'single_choice') {
            $validated = $request->validate([
                'choices' => 'required|array',
            ]);

            $choices_ids = $validated['choices'];
            $choices = $question->choices()->whereIn('id', $choices_ids)->get();

            $question->answers()->createMany($choices->map(function ($choice) {
                return ['choice_id' => $choice->id];
            })->toArray());

        } else {
            $validated = $request->validate([
                'open_answer' => 'required',
            ]);

            $question->answers()->create([
                'open_answer' => $validated['open_answer']
            ]);
        }

        return response()->json(['message' => 'Answer submitted successfully.']);
    }

    /**
     *  Close voting for a question
     */
    public function close(Request $request, Question $question)
    {
        Gate::authorize('close', $question);

        $validated = $request->validate([
            'note' => 'nullable'
        ]);

        $vote_closure = VoteClosure::create([
            'question_id' => $question->id,
            'note' => $validated['note']
        ]);

        return new VoteClosureResource($vote_closure);
    }

    /**
     *  Get closures for a question
     */
    public function closures(Request $request, Question $question)
    {
        Gate::authorize('view_closures', $question);
        return VoteClosureResource::collection($question->closures);
    }


    /**
     * Get latest voting results
     * @response array{ "answer": "Yes", "count": 2 }[]
     */
    public function result(string $code)
    {
        $question = Question::with('choices', 'answers', 'answers.choice')->where('code', $code)->firstOrFail();
        $latest_closure = $question->closures()->latest('created_at')->first();

        $startTime = $latest_closure?->created_at;

        $query = Question::prepareResultQuery($question, $startTime);
        $results = $query->get()->toArray();

        return response()->json($results);
    }


    /**
     * Get voting results for a specific closure
     * @response array{ "closure": VoteClosureResource, "results": array{ "answer": "Yes", "count": 2 } }[]
     */
    public function result_archive(string $question_id, $closure_id)
    {
        $question = Question::with('choices', 'answers', 'answers.choice', 'owner')->findOrFail($question_id);
        Gate::authorize('view_result_archive', $question);

        $closure = $question->closures()->findOrFail($closure_id);
        $older_closure = $question->closures()->where('created_at', '<', $closure->created_at)->latest('created_at')->first();

        $startTime = $older_closure?->created_at;
        $endTime = $closure->created_at;

        $query = Question::prepareResultQuery($question, $startTime, $endTime);
        $results = $query->get()->toArray();

        $response = [
            'closure' => new VoteClosureResource($closure),
            'results' => $results,
        ];

        return response()->json($response);
    }


    /**
     * Get voting results for all closures
     * @response array{ "closure": VoteClosureResource, "results": array{ "answer": "Yes", "count": 2 } }[]
     */
    public function result_compare(string $question_id)
    {
        $question = Question::with('choices', 'answers', 'answers.choice', 'owner')->findOrFail($question_id);
        Gate::authorize('view_result_archive', $question);

        $closures = $question->closures()->orderBy('created_at')->get();
        $comparisons = [];

        $previousClosure = null;
        foreach ($closures as $closure) {
            $startTime = $previousClosure ? $previousClosure->created_at : null;
            $endTime = $closure->created_at;
            $previousClosure = $closure;

            $query = Question::prepareResultQuery($question, $startTime, $endTime);
            $results = $query->get()->toArray();

            $comparisons[] = [
                'closure' => new VoteClosureResource($closure),
                'results' => $results,
            ];
        }

        // Add current voting data
        $latestClosure = $question->closures()->latest('created_at')->first();
        $startTime = $latestClosure?->created_at;
        $currentResults = Question::prepareResultQuery($question, $startTime, now())->get();
        $comparisons[] = [
            'closure' => null,
            'results' => $currentResults,
        ];

        $comparisons = array_reverse($comparisons);

        return response()->json($comparisons);
    }


}
