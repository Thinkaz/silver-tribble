<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        if (app()->environment('prod', 'production')) {
            /** @var User $user */
            $user = User::create([
                'username' => 'License Holder',
                'steamid' => config('cosmo.licensee'),
                'avatar' => asset('img/logo.png'),
            ]);

            $user->assignRole('admin');
        }

        User::create([
            'username' => 'Zeo',
            'steamid' => '76561198251742058',
            'avatar' => 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/5e/5ec69be738df13e9cc072a6169b6af3f7d3c3eab_medium.jpg',
        ])->assignRole(1);
    }
}
