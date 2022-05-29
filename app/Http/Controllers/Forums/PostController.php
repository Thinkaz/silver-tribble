<?php

namespace App\Http\Controllers\Forums;

use App\Http\Controllers\Controller;
use App\Models\Forums\Post;
use App\Models\Forums\Thread;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Mews\Purifier\Facades\Purifier;

class PostController extends Controller
{
    public function store(Thread $thread): RedirectResponse
    {
        $this->authorize('create', [Post::class, $thread]);

        $data = request()->validate([
            'content' => 'required|max:2000'
        ]);

        /** @var Post $post */
        $post = $thread->posts()->create([
            'user_id' => auth()->id(),
            'content' => $data['content'],
        ]);

        return redirect()->route('forums.posts.show', $post->id);
    }

    public function show(Post $post)
    {
        return redirect(route('forums.threads.show', $post->thread_id) . '#post-' . $post->id);
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('forums.posts.edit', compact('post'));
    }

    public function update(Post $post)
    {
        $this->authorize('update', $post);

        $data = request()->validate([
            'content' => 'required|max:2000'
        ]);

        $post->update([
            'content' => $data['content'],
        ]);

        toastr()->success('Successfully updated post!');
        return redirect()->route('forums.posts.show', $post->id);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        toastr()->success('Successfully deleted post!');
        return redirect()->route('forums.threads.show', $post->thread_id);
    }
}
