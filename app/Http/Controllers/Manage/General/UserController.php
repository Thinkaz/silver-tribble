<?php

namespace App\Http\Controllers\Manage\General;

use App\Events\RolesSynced;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\AssignForm;
use App\Http\Requests\Manage\UpdateUserForm;
use App\Models\Role;
use App\Models\Store\Package;
use App\Models\Store\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use PhpParser\Node\Expr\Assign;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-users');
    }

    public function index(Request $request)
    {
        $users = User::with('displayRole')->orderByDesc('created_at');

        if ($search = $request->input('search')) {
            $users->where('username','LIKE',"%$search%")
                ->orWhere('steamid', 'LIKE', "%$search%");
        }

        return view('manage.general.users.index', [
            'users' => $users->paginate(20),
        ]);
    }

    public function edit(User $user)
    {
        $this->authorize('manage', $user);

        $packages = Package::pluck('name', 'id');
        $roles = Role::all(['id', 'name', 'display_name']);

        return view('manage.general.users.edit', [
            'user' => $user,
            'packages' => $packages,
            'roles' => $roles,
        ]);
    }

    public function update(UpdateUserForm $request, User $user): RedirectResponse
    {
        $this->authorize('manage', $user);

        $user->update(
            Arr::only($request->validated(), ['username', 'avatar'])
        );

        $roles = $request->input('roles');
        $user->syncRoles($roles);

        RolesSynced::dispatch($user, $roles);

        $user->profile()->update(
            Arr::only($request->validated(), ['bio', 'signature', 'background_img'])
        );

        toastr()->success('Successfully updated user!');
        return redirect()->route('manage.general.users.edit', $user->steamid);
    }

    public function assign(AssignForm $request, User $user): RedirectResponse
    {
        $package = Package::findOrFail($request->input('package'));

        $trans = Order::create([
            'buyer_id' => $user->id,
            'package_id' => $package->id,
            'receiver' => $user->steamid,
            'status' => Order::STATUS_WAITING_FOR_PACKAGE,
            'price' => 0,
            'assigned' => true
        ]);

        $trans->createActions();

        toastr()->success('Successfully assigned package to the user!');
        return redirect()->back();
    }
}
