<?php

namespace App\Http\Controllers\Manage\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\RoleForm;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-roles');
    }

    public function index(): View
    {
        $roles = Role::withCount(['permissions', 'users'])->orderByDesc('precedence')->get();
        $permissions = Permission::all();

        return view('manage.general.roles', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function store(RoleForm $request): RedirectResponse
    {
        /** @var Role $role */
        $role = Role::create($request->validated());

        $role->givePermissionTo(
            $request->input('permissions')
        );

        toastr()->success('Successfully created a new role!');
        return redirect()->route('manage.general.roles');
    }

    public function update(RoleForm $request, Role $role): RedirectResponse
    {
        $this->authorize('update', $role);

        $role->update($request->validated());

        $role->syncPermissions(
            $request->input('permissions')
        );

        toastr()->success('Successfully updated role!');
        return redirect()->route('manage.general.roles');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('delete', $role);

        $role->delete();

        toastr()->success('Successfully deleted role!');
        return redirect()->route('manage.general.roles');
    }
}
