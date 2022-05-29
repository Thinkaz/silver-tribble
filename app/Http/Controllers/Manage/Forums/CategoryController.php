<?php

namespace App\Http\Controllers\Manage\Forums;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\CategoryForm;
use App\Models\Forums\Category;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-categories');
    }

    public function index()
    {
        $categories = Category::all();

        return view('manage.forums.categories', compact('categories'));
    }

    public function store(CategoryForm $request): RedirectResponse
    {
        Category::create($request->validated());

        toastr()->success('Successfully created new category!');
        return redirect()->route('manage.forums.categories');
    }

    public function update(CategoryForm $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        toastr()->success('Successfully updated category!');
        return redirect()->route('manage.forums.categories');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        toastr()->success('Successfully deleted category!');
        return redirect()->route('manage.forums.categories');
    }
}
