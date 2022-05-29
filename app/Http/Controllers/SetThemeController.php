<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetThemeRequest;
use Illuminate\Http\RedirectResponse;

class SetThemeController extends Controller
{
    public function __invoke(SetThemeRequest $request): RedirectResponse
    {
        $request->session()->put('theme', $request->input('theme'));

        return redirect()->back();
    }
}