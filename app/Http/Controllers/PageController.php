<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \App\Models\Page $page
     * @return \Illuminate\Contracts\View\View
     */
    public function __invoke(Page $page): View
    {
        return view('page', [
            'page' => $page,
        ]);
    }
}
