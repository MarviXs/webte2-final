<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\QuestionClosure;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function index()
    {
        return QuestionResource::collection(Auth::user()->questions);
    }

    public function store(QuestionRequest $request): QuestionResource
    {
        $validated = $request->validated();

        $subject = Subject::firstOrCreate(['name' => $validated['subject']]);
        $validated['subject_id'] = $subject->id;

        $validated['owner_id'] = Auth::id();
        $validated['code'] = Question::generateCode();

        return new QuestionResource(Auth::user()->questions()->create($validated));
    }

    public function show($id)
    {
        $question = Auth::user()->questions()->findOrFail($id);
        return new QuestionResource($question);
    }

    public function update(QuestionRequest $request, $id)
    {
        $validated = $request->validated();
        $question = Auth::user()->questions()->findOrFail($id);

        $subject = Subject::firstOrCreate(['name' => $validated['subject']]);
        $questionData['subject_id'] = $subject->id;

        $question->update($questionData);
        return new QuestionResource($question);
    }

    public function destroy($id)
    {
        $question = Auth::user()->questions()->findOrFail($id);
        $question->delete();
        return response()->json(['message' => 'Question deleted successfully.'], 204);
    }


    public function close(Request $request, string $id)
    {
        $validated = $request->validate([
            'note' => 'nullable'
        ]);

        $question = Auth::user()->questions()->findOrFail($id);

        $question_closure = QuestionClosure::create([
            'question_id' => $question->id,
            'note' => $validated['note']
        ]);

        return $question_closure;
    }
}
