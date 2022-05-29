<?php

namespace App\Events;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class RoleAssigned
{
    use Dispatchable;

    public User $user;
    public Role $role;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param Role $role
     * @return void
     */
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }
}
