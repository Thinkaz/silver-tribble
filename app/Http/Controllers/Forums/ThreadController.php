<?php

namespace App\Http\Controllers\Forums;

use App\Achievements\FirstThread;
use App\Events\ThreadActionExecuted;
use App\Http\Controllers\Controller;
use App\Http\Requests\ThreadForm;
use App\Models\Forums\Board;
use App\Models\Forums\Category;
use App\Models\Forums\Thread;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Mews\Purifier\Facades\Purifier;

class ThreadController extends Controller
{
    public function index()
    {
        $search = request('search');
        $query = Thread::with('user', 'posts');

        if ($search) {
            $query->where('title', 'LIKE', '%'.$search.'%');
        }

        return view('forums.threads.index', [
            'threads' => $query->paginate(20)->appends('search', $search),
        ]);
    }

    public function search(): RedirectResponse
    {
        return redirect()->route('forums.threads', [
            'search' => request()->input('search'),
        ]);
    }

    public function create(Board $board)
    {
        $this->authorize('create', [Thread::class, $board]);

        return view('forums.threads.create', compact('board'));
    }

    public function store(ThreadForm $request, Board $board)
    {
        $this->authorize('create', [Thread::class, $board]);

        /** @var Thread $thread */
        $thread = $board->threads()->create([
            'title'   => request('title'),
            'content' => $request->input('content'),
            'user_id' => auth()->id(),
        ]);

        if (! $request->user()->hasAchievement(FirstThread::class)) // TODO: move this logic to event listener
            $request->user()->achieve(FirstThread::class);

        toastr()->success('Successfully created new thread!');
        return redirect()->route('forums.threads.show', $thread->id);
    }

    public function show(Thread $thread)
    {
        $thread->load('reactions')
            ->loadAvg('reputation', 'rating');

        $posts = $thread->posts()
            ->with([
                'user', 'user.displayRole', 'user.profile', 'reactions',
            ])
            ->withAvg('reputation', 'rating')
            ->paginate(8);

        [$categories, $canManageThreads, $canMoveThreads] = [null, false, false];
        if (auth()->check()) {
            $canManageThreads = auth()->user()->hasPermissionTo('manage-threads');

            if ($canManageThreads && $canMoveThreads = auth()->user()->hasPermissionTo('move-threads')) {
                $categories = Category::with('boards')->get();
            }
        }

        return view('forums.threads.show', compact(
            'thread', 'posts', 'categories', 'canManageThreads', 'canMoveThreads'
        ));
    }

    public function edit(Thread $thread)
    {
        $this->authorize('update', $thread);

        return view('forums.threads.edit', compact('thread'));
    }

    public function update(ThreadForm $request, Thread $thread): RedirectResponse
    {
        $this->authorize('update', $thread);

        $thread->update($request->validated());

        toastr()->success('Successfully updated thread!');
        return redirect()->route('forums.threads.show', $thread->id);
    }

    public function destroy(Thread $thread): RedirectResponse
    {
        $this->authorize('delete', $thread);

        $thread->delete();

        toastr()->success('Successfully deleted thread!');
        return redirect()->route('forums.boards.show', $thread->board_id);
    }

    public function sticky(Thread $thread): RedirectResponse
    {
        $this->authorize('sticky', $thread);

        $thread->update([
            'stickied' => ! ($thread->stickied),
        ]);

        ThreadActionExecuted::dispatch($thread, auth()->user(), 'stickied');

        toastr()->success('Successfully '.($thread->stickied ? '' : 'un').'pinned thread!');
        return redirect()->route('forums.threads.show', $thread->id);
    }

    public function lock(Thread $thread): RedirectResponse
    {
        $this->authorize('lock', $thread);

        $thread->update([
            'locked' => ! ($thread->locked),
        ]);

        ThreadActionExecuted::dispatch($thread, auth()->user(), 'locked');

        toastr()->success('Successfully '.($thread->locked ? '' : 'un').'locked thread!');
        return redirect()->route('forums.threads.show', $thread->id);
    }

    public function move(Thread $thread): RedirectResponse
    {
        $this->authorize('move', $thread);

        $boardId = request('board');
        if (is_null($boardId)) abort(404);

        $board = Board::findOrFail($boardId);

        $thread->update([
            'board_id' => $board->id,
        ]);

        ThreadActionExecuted::dispatch($thread, auth()->user(), 'moved');

        toastr()->success('Successfully moved the thread!');
        return redirect()->route('forums.threads.show', $thread->id);
    }
}
