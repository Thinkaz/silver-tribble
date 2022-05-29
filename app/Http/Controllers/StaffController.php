<?php

namespace App\Http\Controllers;

use App\Models\User;

class StaffController extends Controller
{
    // TODO: optimize queries
    public function __invoke()
    {
        $users = User::with('roles')->get()
            ->filter(fn (User $user) => $user->hasPermissionTo('display-staff'))
            ->sortByDesc(fn (User $user) => $user->roles->sortByDesc('precedence')->first()->precedence);

        return view('staff', [
            'users' => $users,
        ]);
    }
}
