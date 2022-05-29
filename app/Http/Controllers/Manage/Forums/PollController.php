<?php

namespace App\Http\Controllers\Manage\Forums;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\PollForm;
use App\Models\Forums\Poll;
use Illuminate\Http\RedirectResponse;

class PollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-polls');
    }

    public function index()
    {
        $polls = Poll::with('userAnswers', 'userAnswers.user')->get();

        return view('manage.forums.polls', compact('polls'));
    }

    public function store(PollForm $request): RedirectResponse
    {
        Poll::create($request->validated());

        toastr()->success('Successfully started a new poll!');
        return redirect()->route('manage.forums.polls');
    }

    public function update(PollForm $request, Poll $poll): RedirectResponse
    {
        $poll->update($request->validated());

        toastr()->success('Successfully updated the poll!');
        return redirect()->route('manage.forums.polls');
    }

    public function close(Poll $poll): RedirectResponse
    {
        $poll->update([
            'closed' => !$poll->closed
        ]);

        toastr()->success('Successfully ' . ($poll->closed ? 'closed' : 'opened') . ' the poll!');
        return redirect()->route('manage.forums.polls');
    }

    public function destroy(Poll $poll): RedirectResponse
    {
        $poll->delete();

        toastr()->success('Successfully deleted the poll!');
        return redirect()->route('manage.forums.polls');
    }
}
