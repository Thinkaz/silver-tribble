<?php

namespace App\Http\Controllers\Manage\Index;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\FooterLinkForm;
use App\Models\Index\FooterLink;
use Illuminate\Http\RedirectResponse;

class FooterLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-footerlinks');
    }

    public function index()
    {
        $links = FooterLink::all();
        return view('manage.index.components.footerlinks', compact('links'));
    }

    public function store(FooterLinkForm $request): RedirectResponse
    {
        FooterLink::create($request->validated());

        toastr()->success('Successfully created footer link!');
        return redirect()->route('manage.index.footerlinks');
    }

    public function update(FooterLinkForm $request, FooterLink $link): RedirectResponse
    {
        $link->update($request->validated());

        toastr()->success('Successfully updated footer link!');
        return redirect()->route('manage.index.footerlinks');
    }

    public function destroy(FooterLink $link): RedirectResponse
    {
        $link->delete();

        toastr()->success('Successfully deleted footer link!');
        return redirect()->route('manage.index.footerlinks');
    }
}
