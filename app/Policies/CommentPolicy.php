<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Profile\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Comment $comment
     * @return bool|void
     */
    public function update(User $user, Comment $comment)
    {
        if ($comment->user_id === $user->id) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Comment $comment
     * @return bool|void
     */
    public function delete(User $user, Comment $comment)
    {
        if ($user->id === $comment->user_id || $comment->profile->user_id === $user->id) {
            return true;
        }

        if ($user->hasPermissionTo('manage-users')) {
            return true;
        }
    }
}
