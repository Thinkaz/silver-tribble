<?php

namespace App\Http\Controllers\Manage\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\PageRequest;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-pages');
    }

    public function index(): View
    {
        $pages = Page::paginate(16);

        return view('manage.general.pages.index', [
            'pages' => $pages,
        ]);
    }

    public function create(): View
    {
        return view('manage.general.pages.create');
    }

    public function store(PageRequest $request): RedirectResponse
    {
        Page::create(
            $request->validated()
        );

        toastr()->success('Successfully created a new page!');
        return redirect()->route('manage.general.pages.index');
    }

    public function edit(Page $page): View
    {
        return view('manage.general.pages.edit', [
            'page' => $page,
        ]);
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $page->update(
            $request->validated()
        );

        toastr()->success('Successfully updated the page!');
        return redirect()->back();
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        toastr()->success('Successfully deleted the page!');
        return redirect()->back();
    }
}
