<?php

namespace App\Http\Controllers\Manage\Index;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\NavLinkForm;
use App\Models\Index\NavLink;
use Illuminate\Http\RedirectResponse;

class NavLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-navlinks');
    }

    public function index()
    {
        $links = NavLink::all();

        return view('manage.index.components.navlinks', compact('links'));
    }

    public function store(NavLinkForm $request): RedirectResponse
    {
        NavLink::create($request->validated());

        toastr()->success('Successfully created a navigation link!');
        return redirect()->route('manage.index.navlinks');
    }

    public function update(NavLinkForm $request, NavLink $link): RedirectResponse
    {
        $link->update($request->validated());

        toastr()->success('Successfully updated navigation link!');
        return redirect()->route('manage.index.navlinks');
    }

    public function destroy(NavLink $link): RedirectResponse
    {
        $link->delete();

        toastr()->success('Successfully deleted navigation link!');
        return redirect()->route('manage.index.navlinks');
    }
}
