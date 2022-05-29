<?php

namespace App\Policies;

use App\Models\Forums\Board;
use App\Models\User;
use App\Models\Forums\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create a thread.
     *
     * @param User $user
     * @param Board $board
     * @return bool|void
     */
    public function create(User $user, Board $board)
    {
        if ($user->hasAnyRole($board->roles)) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the thread.
     *
     * @param User $user
     * @param Thread $thread
     * @return bool|void
     */
    public function update(User $user, Thread $thread)
    {
        if ($user->is($thread->user)) {
            return true;
        }

        if ($user->hasPermissionTo('manage-threads')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete a thread.
     *
     * @param User $user
     * @param Thread $thread
     * @return bool|void
     */
    public function delete(User $user, Thread $thread)
    {
        if ($user->is($thread->user)) {
            return true;
        }

        if ($user->hasPermissionTo('manage-threads')) {
            return true;
        }
    }

    /**
     * Determines whether a user can sticky a thread.
     *
     * @param User $user
     * @return bool|void
     */
    public function sticky(User $user)
    {
        if ($user->hasPermissionTo('sticky-threads')) {
            return true;
        }
    }

    /**
     * Determines whether a user can lock a thread.
     *
     * @param User $user
     * @return bool|void
     */
    public function lock(User $user)
    {
        if ($user->hasPermissionTo('lock-threads')) {
            return true;
        }
    }

    /**
     * Determines whether a user can move a thread.
     *
     * @param User $user
     * @return bool|void
     */
    public function move(User $user)
    {
        if ($user->hasPermissionTo('move-threads')) {
            return true;
        }
    }
}
