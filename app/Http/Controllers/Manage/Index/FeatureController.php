<?php

namespace App\Http\Controllers\Manage\Index;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\FeatureForm;
use App\Models\Index\Feature;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FeatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-features');
    }

    public function index()
    {
        $features = Feature::all();

        return view('manage.index.components.features', compact('features'));
    }

    public function store(FeatureForm $request): RedirectResponse
    {
        Feature::create($request->validated());

        toastr()->success('Successfully created new feature!');
        return redirect()->route('manage.index.features');
    }

    public function update(FeatureForm $request, Feature $feature): RedirectResponse
    {
        $feature->update($request->validated());

        toastr()->success('Successfully updated the feature!');
        return redirect()->route('manage.index.features');
    }

    public function destroy(Feature $feature): RedirectResponse
    {
        $feature->delete();

        toastr()->success('Successfully deleted feature!');
        return redirect()->route('manage.index.features');
    }
}
