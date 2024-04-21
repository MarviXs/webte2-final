<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChoiceResource;
use App\Models\Choice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChoiceController extends Controller
{

    public function store(Request $request, $question_id): ChoiceResource
    {
        $validated = $request->validate([
            'choice_text' => 'required|string',
        ]);
        $this->checkUserOwnsQuestion($question_id);
        $validated['question_id'] = $question_id;

        $choice = Choice::create($validated);
        return new ChoiceResource($choice);
    }

    public function update(Request $request, Choice $choice): ChoiceResource
    {
        $validated = $request->validate([
            'choice_text' => 'required|string',
        ]);
        $this->checkUserOwnsQuestion($choice->question_id);
        $choice->update($validated);

        return new ChoiceResource($choice);
    }

    public function destroy(Choice $choice, $question_id)
    {
        $this->checkUserOwnsQuestion($question_id);
        $choice->delete();

        return response()->json(['message' => 'Choice deleted successfully.'], 204);
    }

    protected function checkUserOwnsQuestion(string $questionId): void
    {
        $userOwnsQuestion = Auth::user()->questions()->where('id', $questionId)->exists();
        if (!$userOwnsQuestion) {
            abort(404, 'Question not found');
        }
    }
}
