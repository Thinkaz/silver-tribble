<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReputationRequest;
use App\Models\Reputation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class ReputationController extends Controller
{
    public function __invoke(User $user, ReputationRequest $request): RedirectResponse
    {
        Reputation::updateOrCreate([
            'user' => $request->user(),
            'reputable' => $user,
        ], [
            'rating' => $request->get('rating'),
        ]);

        return redirect()->back();
    }
}
