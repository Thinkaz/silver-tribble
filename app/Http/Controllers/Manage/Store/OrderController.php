<?php

namespace App\Http\Controllers\Manage\Store;

use App\Http\Controllers\Controller;
use App\Models\Store\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-transactions');
    }

    public function index()
    {
        $transactions = Order::with('package', 'buyer')->paginate(15);
        return view('manage.store.transactions', compact('transactions'));
    }

    public function prunePending(): RedirectResponse
    {
        Artisan::call('prune:pending-orders');

        toastr()->success('Successfully pruned all pending orders!');
        return redirect()->back();
    }
}
