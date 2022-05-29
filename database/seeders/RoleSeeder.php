<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::create([
            'name' => 'user',
            'display_name' => 'User',
            'color' => '#9E9E9E',
            'precedence' => 1,
            'deletable' => false,
        ]);

        /** @var Role $adminRole */
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'color' => '#F44336',
            'precedence' => 100,
            'deletable' => false
        ]);

        $adminPerms = Permission::all(['name'])
            ->map(fn (Permission $permission) => $permission->name)
            ->toArray();

        $adminRole->givePermissionTo($adminPerms);
    }
}
