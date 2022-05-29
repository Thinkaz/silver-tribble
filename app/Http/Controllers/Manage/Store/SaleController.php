<?php

namespace App\Http\Controllers\Manage\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\SaleForm;
use App\Models\Store\Package;
use App\Models\Store\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-sales');
    }

    public function index()
    {
        $sales = Sale::all();
        $packages = Package::pluck('name', 'id');

        return view('manage.store.sales', compact('sales', 'packages'));
    }

    public function store(SaleForm $request): RedirectResponse
    {
        $sale = Sale::create($request->validated());

        $packages = trim($request->post('packages'));
        $packages = (Str::length($packages) > 0) ? explode(",", $packages) : null;
        if ($packages) $sale->packages()->attach($packages);

        toastr()->success("Successfully created new sale!");
        return redirect()->route('manage.store.sales');
    }

    public function update(SaleForm $request, Sale $sale): RedirectResponse
    {
        $sale->update($request->validated());

        $packages = trim($request->post('packages'));
        $packages = (Str::length($packages) > 0) ? explode(",", $packages) : null;
        $sale->packages()->sync($packages ?? []);

        toastr()->success("Successfully updated sale!");
        return redirect()->route('manage.store.sales');
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        $sale->delete();

        toastr()->success("Successfully deleted sale!");
        return redirect()->route('manage.store.sales');
    }
}
