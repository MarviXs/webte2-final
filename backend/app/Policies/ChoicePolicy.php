<?php

namespace App\Policies;

use App\Models\Choice;
use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChoicePolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Question $question): bool
    {
        return $user->id === $question->owner_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Choice $choice): bool
    {
        return $user->id === $choice->question->owner_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Choice $choice): bool
    {
        return $user->id === $choice->question->owner_id || $user->isAdmin();
    }
}
