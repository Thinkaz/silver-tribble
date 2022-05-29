<?php

namespace App\Events;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class RolesSynced
{
    use Dispatchable;

    public User $user;

    /** @var Role[] $roles */
    public array $roles;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, array $roles)
    {
        $this->user = $user;
        $this->roles = $roles;
    }
}
