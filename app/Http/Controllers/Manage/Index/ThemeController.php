<?php

namespace App\Http\Controllers\Manage\Index;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Igaster\LaravelTheme\Facades\Theme;
use Illuminate\Http\RedirectResponse;

class ThemeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-theme');
    }

    public function index()
    {
        $themes = Theme::all();
        $current = config('cosmo.configs.active_theme', config('themes.default'));

        return view('manage.index.theme', compact('themes', 'current'));
    }

    public function update($theme): RedirectResponse
    {
        if(!Theme::exists($theme)) {
            abort(404);
        }

        Configuration::firstWhere('key', 'active_theme')->update([
            'value' => $theme
        ]);

        toastr()->success('Successfully updated the theme!');
        return redirect()->route('manage.index.theme');
    }
}
