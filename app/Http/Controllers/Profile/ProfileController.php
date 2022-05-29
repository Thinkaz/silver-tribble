<?php

namespace App\Http\Controllers\Profile;

use App\Achievements\Designer;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileForm;
use App\Models\Store\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ProfileController extends Controller
{
    public function index()
    {
        $search = request('search');
        $query = User::with('displayRole');

        if ($search) {
            $query
                ->where('username', 'LIKE', '%' . request()->input('search') . '%')
                ->orWhere('steamid', 'LIKE', '%' . request()->input('search') . '%');
        }

        return view('users.index', [
            'search' => $search,
            'users' => $query
                ->orderBy('created_at', 'DESC')
                ->paginate(16)
                ->appends('search', $search)
        ]);
    }

    public function search(): RedirectResponse
    {
        return redirect()->route('users.index', [
            'search' => request('search')
        ]);
    }

    public function show(User $user)
    {
        $user->loadAvg('reputation', 'rating')
            ->profile->loadCount('likes');

        return view('users.show.index', [
            'user' => $user
        ]);
    }

    public function comments(User $user)
    {
        $user->profile->loadCount('likes');

        $comments = $user->profile->comments()
            ->with(['user', 'user.profile', 'user.displayRole'])
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('users.show.comments', [
            'user' => $user,
            'comments' => $comments
        ]);
    }

    public function threads(User $user)
    {
        $user->profile->loadCount('likes');

        $threads = $user->threads()
            ->withCount('posts')
            ->paginate(8);

        return view('users.show.threads', [
            'user' => $user,
            'threads' => $threads
        ]);
    }

    public function achievements(User $user)
    {
        $user->profile->loadCount('likes');

        return view('users.show.achievements', [
            'user' => $user
        ]);
    }

    public function storeStatistics(User $user)
    {
        $this->authorize('viewStoreStatistics', $user->profile);

        $totalSpent = $user->orders
            ->whereIn('status', [Order::STATUS_WAITING_FOR_PACKAGE, Order::STATUS_DELIVERED])
            ->sum('price');

        $monthlySpending = $user->orders
            ->whereIn('status', [Order::STATUS_WAITING_FOR_PACKAGE, Order::STATUS_DELIVERED])
            ->filter(fn (Order $order) => $order->created_at->isCurrentMonth())
            ->sum('price');

        $weeklySpending = $user->orders
            ->whereIn('status', [Order::STATUS_WAITING_FOR_PACKAGE, Order::STATUS_DELIVERED])
            ->filter(fn (Order $order) => $order->created_at->isCurrentWeek())
            ->sum('price');

        $orders = $user->orders()
            ->orderByDesc('created_at')
            ->whereHas('package')
            ->with('package')
            ->paginate(8);

        $yearlySpendingGraph = $user->orders
            ->whereIn('status', [Order::STATUS_WAITING_FOR_PACKAGE, Order::STATUS_DELIVERED])
            ->filter(fn (Order $order) => $order->created_at->isCurrentYear())
            ->groupBy(fn (Order $order) => $order->created_at->month)
            ->map(fn (Collection $orders) => $orders->sum('price'));

        $monthlySpendingGraph = $user->orders
            ->whereIn('status', [Order::STATUS_WAITING_FOR_PACKAGE, Order::STATUS_DELIVERED])
            ->filter(fn (Order $order) => $order->created_at->isCurrentMonth())
            ->groupBy(fn (Order $order) => $order->created_at->day)
            ->map(fn (Collection $orders) => $orders->sum('price'));

        return view('users.show.store', [
            'user' => $user,
            'totalSpent' => $totalSpent,
            'monthlySpending' => $monthlySpending,
            'weeklySpending' => $weeklySpending,
            'orders' => $orders,
            'yearlySpendingGraph' => $yearlySpendingGraph,
            'monthlySpendingGraph' => $monthlySpendingGraph,
        ]);
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);
        $user->profile->loadCount('likes');

        return view('users.show.edit', compact('user'));
    }

    public function update(ProfileForm $request, User $user)
    {
        $this->authorize('update', $user->profile);

        $user->update(Arr::only($request->validated(), ['username']));
        $user->profile->update(Arr::except($request->validated(), ['username']));

        if (!$request->user()->hasAchievement(Designer::class)) {
            $request->user()->achieve(Designer::class);
        }

        toastr()->success('Successfully updated profile!');
        return redirect()->route('users.show', $user->steamid);
    }
}
