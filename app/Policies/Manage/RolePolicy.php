<?php

namespace App\Policies\Manage;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Role $role)
    {
        $highestRole = $user->roles()->orderByDesc('precedence')->first();
        if ($highestRole && $highestRole->precedence >= $role->precedence) {
            return true;
        }
    }

    public function delete(User $user, Role $role)
    {
        if (!$role->deletable) {
            return false;
        }

        $highestRole = $user->roles()->orderByDesc('precedence')->first();
        if ($highestRole && $highestRole->precedence >= $role->precedence) {
            return true;
        }
    }
}
