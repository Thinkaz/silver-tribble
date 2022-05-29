<?php

namespace App\Policies;

use App\Models\Forums\Post;
use App\Models\Forums\Thread;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determines whether a user is allowed to create a post.
     *
     * @param User $user
     * @param Thread $thread
     * @return bool|void
     */
    public function create(User $user, Thread $thread)
    {
        if (!$thread->locked) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Post $post
     * @return bool|void
     */
    public function update(User $user, Post $post)
    {
        if ($user->is($post->user)) {
            return true;
        }

        if ($user->hasPermissionTo('manage-threads')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Post $post
     * @return bool|void
     */
    public function delete(User $user, Post $post)
    {
        if ($user->is($post->user)) {
            return true;
        }

        if ($user->hasPermissionTo('manage-threads')) {
            return true;
        }
    }
}
