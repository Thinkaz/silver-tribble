<?php

namespace App\Http\Controllers\Manage\General;

use App\Events\ChangelogCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\ChangelogForm;
use App\Models\Changelog;
use App\Models\ChangelogLabel;
use App\Models\Configuration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

class ChangelogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-changelogs');
    }

    public function toggle(): RedirectResponse
    {
        $state = (bool) config('cosmo.configs.changelogs_enabled', false);

        Configuration::where('key', 'changelogs_enabled')
            ->update([
                'value' => !$state
            ]);

        Artisan::call('cache:clear');

        toastr()->success('Successfully turned changelogs ' . ($state ? 'off' : 'on') . '!');
        return redirect()->back();
    }

    public function index()
    {
        $changelogs = Changelog::with('labels')->paginate(10);
        $labels = ChangelogLabel::all();

        return view('manage.general.changelogs.index', [
            'changelogs' => $changelogs,
            'labels' => $labels
        ]);
    }

    public function create()
    {
        $labels = ChangelogLabel::all(['id', 'name', 'color']);

        return view('manage.general.changelogs.create', [
            'labels' => $labels
        ]);
    }

    public function store(ChangelogForm $request): RedirectResponse
    {
        $changelog = Changelog::create($request->validated());
        $changelog->labels()->attach($request->input('labels'));

        event(new ChangelogCreated($changelog));

        toastr()->success('Successfully created a new changelog!');
        return redirect()->route('manage.general.changelogs');
    }

    public function edit(Changelog $changelog)
    {
        $labels = ChangelogLabel::all(['id', 'name', 'color']);

        return view('manage.general.changelogs.edit', [
            'changelog' => $changelog,
            'labels' => $labels
        ]);
    }

    public function update(ChangelogForm $request, Changelog $changelog): RedirectResponse
    {
        $changelog->update($request->validated());
        $changelog->labels()->sync($request->labels);

        toastr()->success('Successfully updated the changelog!');
        return redirect()->back();
    }

    public function destroy(Changelog $changelog): RedirectResponse
    {
        $changelog->delete();

        toastr()->success('Successfully deleted the changelog.');
        return redirect()->back();
    }
}
