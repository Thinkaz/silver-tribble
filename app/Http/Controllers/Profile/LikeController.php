<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function like(User $user)
    {
        $profile = $user->profile;

        if ($user->is(auth()->user())) {
            return response([
                'error' => 'You can\'t like your own profile :(',
            ], 400);
        }

        $authId = auth()->id();
        if ($existingLike = $profile->likes()->where('user_id', $authId)->first()) {
            $existingLike->delete();

            return response(null, 204);
        }

        $profile->likes()->create(['user_id' => $authId]);

        return response(null, 201);
    }
}
