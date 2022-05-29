<?php

namespace App\Listeners;

use App\Events\RoleAssigned;
use App\Events\RoleDeleted;
use App\Events\RolesSynced;
use App\Events\RoleUnassigned;
use App\Events\RoleUpdated;
use App\Events\UserCreated;
use App\Models\Role;
use App\Models\User;

class UpdateUserDisplayRoleSubscriber
{
    /**
     * Triggered when a role gets updated, this should update all the users
     * display roles which have a lower precedence than the current display role.
     *
     * @param RoleUpdated $event
     * @return void
     */
    public function handleRoleUpdated(RoleUpdated $event)
    {
        $role = $event->role;
        $users = $role->users()->with(['displayRole'])->get();

        /** @var User $user */
        foreach ($users as $user) {
            if ($user->displayRole && $user->displayRole->precedence > $role->precedence) {
                continue;
            }

            $user->displayRole()->associate($event->role);
            $user->save();
        }
    }

    /**
     * Triggered when a role gets deleted, this should update the
     * user's display role if the current one is the one that got deleted.
     *
     * @param RoleDeleted $event
     * @return void
     */
    public function handleRoleDeleted(RoleDeleted $event)
    {
        $users = $event->role->users()->with(['displayRole', 'roles'])->get();

        /** @var User $user */
        foreach ($users as $user) {
            if (!$user->displayRole->is($event->role)) continue;

            $highestPrecedenceRole = $user->roles->sortByDesc('precedence')->first();

            $user->displayRole()->associate($highestPrecedenceRole);
            $user->save();
        }
    }

    /**
     * Triggered when a role gets assigned to a user, this should update
     * the user's display role if they do not have one yet or the assigned role
     * has a higher precedence than the current display role.
     *
     * @param RoleAssigned $event
     * @return void
     */
    public function handleRoleAssigned(RoleAssigned $event)
    {
        $user = $event->user;

        if (!$user->displayRole || ($user->displayRole->precedence < $event->role->precedence)) {
            $user->displayRole()->associate($event->role);
            $user->save();
        }
    }

    /**
     * Triggered when a role gets unassigned from a user, this should update
     * the user's display role if it's the unassigned role.
     *
     * @param RoleUnassigned $event
     * @return void
     */
    public function handleRoleUnassigned(RoleUnassigned $event)
    {
        $user = $event->user;

        if ($user->displayRole && $user->displayRole->is($event->role)) {
            $highestPrecedenceRole = $user->roles
                ->where('id', '!=', $event->role->id)
                ->sortByDesc('precedence')
                ->first();

            $user->displayRole()->associate($highestPrecedenceRole);
            $user->save();
        }
    }

    /**
     * Triggered when a user's roles get synced, this should update the users display
     * role to the highest new role.
     *
     * @param RolesSynced $event
     * @return void
     */
    public function handleRolesSynced(RolesSynced $event)
    {
        if (count($event->roles) === 0) return;

        $user = $event->user;

        $highestRole = Role::whereIn('id', $event->roles)
            ->orderByDesc('precedence')
            ->first();

        $user->displayRole()->associate($highestRole);
        $user->save();
    }

    /**
     * Triggered when a user got created, this should assign a default display
     * role.
     *
     * @param UserCreated $event
     * @return void
     */
    public function handleUserCreated(UserCreated $event)
    {
        $user = $event->user;
        $role = $user->roles()->first();

        if (!$role) {
            $role = Role::find(1); // User role

            $user->assignRole($role);
        }

        $user->displayRole()->associate($role);
        $user->save();
    }

    /**
     * Handle the event.
     *
     * @return array
     */
    public function subscribe(): array
    {
        return [
            RoleUpdated::class => 'handleRoleUpdated',
            RoleDeleted::class => 'handleRoleDeleted',
            RoleAssigned::class => 'handleRoleAssigned',
            RoleUnassigned::class => 'handleRoleUnassigned',
            RolesSynced::class => 'handleRolesSynced',
            UserCreated::class => 'handleUserCreated',
        ];
    }
}
