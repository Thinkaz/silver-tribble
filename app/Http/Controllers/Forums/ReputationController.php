<?php

namespace App\Http\Controllers\Forums;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReputationRequest;
use App\Models\Forums\Post;
use App\Models\Forums\Thread;
use App\Models\Reputation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class ReputationController extends Controller
{
    public function thread(Thread $thread, ReputationRequest $request): RedirectResponse
    {
        Reputation::updateOrCreate([
            'user' => $request->user(),
            'reputable' => $thread,
        ], [
            'rating' => $request->get('rating'),
        ]);

        return redirect()->back();
    }

    public function post(Post $post, ReputationRequest $request): RedirectResponse
    {
        Reputation::updateOrCreate([
            'user' => $request->user(),
            'reputable' => $post,
        ], [
            'rating' => $request->get('rating'),
        ]);

        return redirect()->back();
    }
}
