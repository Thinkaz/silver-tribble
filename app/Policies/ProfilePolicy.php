<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Determines whether a user can update a profile.
     *
     * @param User $user
     * @param Profile $profile
     * @return bool|void
     */
    public function update(User $user, Profile $profile)
    {
        if ($user->is($profile->user)) {
            return true;
        }
    }

    /**
     * Determines whether a user can store a profile.
     *
     * @param User $user
     * @param Profile $profile
     * @return bool|void
     */
    public function store(User $user, Profile $profile)
    {
        if ($user->is($profile->user)) {
            return true;
        }

        if ($user->hasPermissionTo('manage-users')) {
            return true;
        }
    }

    /**
     * Determines whether a user can view a profile's store statistics.
     *
     * @param User $user
     * @param Profile $profile
     * @return bool|void
     */
    public function viewStoreStatistics(User $user, Profile $profile)
    {
        if ($user->is($profile->user)) {
            return true;
        }

        if ($user->hasPermissionTo('manage-users')) {
            return true;
        }
    }
}
