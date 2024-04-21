<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuestionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Question $question): Response
    {
        return $user->isAdmin() || $this->isOwner($user, $question)
            ? Response::allow()
            : Response::deny('You do not own this question.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Question $question): bool
    {
        return $user->isAdmin() || $this->isOwner($user, $question);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Question $question): bool
    {
        return $user->isAdmin() || $this->isOwner($user, $question);
    }

    /**
     * Determine whether the user can close voting.
     */
    public function close(User $user, Question $question): bool
    {
        return $user->isAdmin() || $this->isOwner($user, $question);
    }


    public function view_closures(User $user, Question $question): bool
    {
        return $user->isAdmin() || $this->isOwner($user, $question);
    }

    public function view_result_archive(User $user, Question $question): bool
    {
        return $user->isAdmin() || $this->isOwner($user, $question);
    }


    private function isOwner(User $user, Question $question): bool
    {
        return $user->id === $question->owner_id;
    }

}
