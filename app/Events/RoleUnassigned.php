<?php

namespace App\Events;

use App\Models\Role;
use App\Models\User;

class RoleUnassigned
{
    public User $user;
    public Role $role;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }
}
