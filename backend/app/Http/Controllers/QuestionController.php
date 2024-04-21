<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionAdminResource;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class QuestionController extends Controller
{
    public function index()
    {
        return QuestionResource::collection(Auth::user()->questions);
    }

    public function index_admin()
    {
        Gate::authorize('viewAny', Question::class);

        return QuestionAdminResource::collection(Question::all());
    }

    public function store(QuestionRequest $request): QuestionResource
    {
        Gate::authorize('create', Question::class);

        $validated = $request->validated();

        $subject = Subject::firstOrCreate(['name' => $validated['subject']]);
        $validated['subject_id'] = $subject->id;
        $validated['code'] = Question::generateCode();

        return new QuestionResource(Auth::user()->questions()->create($validated));
    }

    public function show(Question $question)
    {
        Gate::authorize('view', $question);

        return new QuestionResource($question);
    }

    public function update(QuestionRequest $request, Question $question)
    {
        Gate::authorize('update', $question);

        $validated = $request->validated();
        $subject = Subject::firstOrCreate(['name' => $validated['subject']]);
        $questionData['subject_id'] = $subject->id;

        $question->update($questionData);
        return new QuestionResource($question);
    }

    public function destroy(Question $question)
    {
        Gate::authorize('delete', $question);

        $question->delete();
        return response()->json(['message' => 'Question deleted successfully.'], 204);
    }
}
