<?php

namespace App\Policies\Manage;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determines if the user is able to manage a certain user.
     *
     * @param User $user
     * @param User $target
     * @return bool|void
     */
    public function manage(User $user, User $target)
    {
        if (!$target->displayRole) {
            return true;
        }

        $userPrecedence = optional($user->displayRole)->precedence ?? 0;
        if ($userPrecedence >= $target->displayRole->precedence) {
            return true;
        }
    }
}
