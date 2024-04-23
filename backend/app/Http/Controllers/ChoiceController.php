<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChoiceResource;
use App\Models\Choice;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChoiceController extends Controller
{

    /**
     * Create a new choice for a question
     */
    public function store(Request $request, $question_id): ChoiceResource
    {
        $validated = $request->validate([
            'choice_text' => 'required|string',
            'order' => 'integer',
        ]);

        $question = Question::findOrFail($question_id);
        Gate::authorize('create', [Choice::class, $question]);

        $validated['question_id'] = $question_id;

        $choice = Choice::create($validated);
        return new ChoiceResource($choice);
    }

    /**
     * Update a choice
     */
    public function update(Request $request, $question_id, $choice_id): ChoiceResource
    {
        $choice = Choice::findOrFail($choice_id);
        Gate::authorize('update', $choice);

        $validated = $request->validate([
            'choice_text' => 'required|string',
            'order' => 'integer',
        ]);

        $choice->update($validated);

        return new ChoiceResource($choice);
    }

    /**
     * Delete a choice
     */
    public function destroy($choice_id)
    {
        $choice = Choice::findOrFail($choice_id);
        Gate::authorize('delete', $choice);
        $choice->delete();

        return response()->json(['message' => 'Choice deleted successfully.'], 204);
    }
}
