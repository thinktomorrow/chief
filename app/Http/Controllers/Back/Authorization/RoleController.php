<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Admin\Authorization\Permission;
use Thinktomorrow\Chief\Admin\Authorization\Role;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->authorize('view-role');

        return view('chief::admin.authorization.roles.index', [
            'roles' => Role::all(),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->authorize('create-role');

        return view('chief::admin.authorization.roles.create', [
            'role' => new Role(),
            'permission_names' => Permission::all()->pluck('name')->toArray(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create-role');

        $this->validate($request, [
            'name' => 'required|unique:roles',
            'permission_names' => 'required|array',
            'permission_names.*' => 'required', // Avoid null array entry
        ]);

        $role = Role::create($request->only('name'));
        $role->givePermissionTo($request->permission_names);

        return redirect()->route('chief.back.roles.index')
                         ->with('messages.success', 'Rol ' . $role->name . ' is toegevoegd.');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $this->authorize('update-role');

        $role = Role::findOrFail($id);
        $permission_names = Permission::all()->pluck('name')->toArray();

        return view('chief::admin.authorization.roles.edit', compact('role', 'permission_names'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update-role');

        $this->validate($request, [
            'name' => 'required|alpha_dash|unique:roles,name,' . $id,
            'permission_names' => 'required|array',
            'permission_names.*' => 'required', // Avoid null array entry
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permission_names);

        return redirect()->route('chief.back.roles.index')
            ->with('messages.success', 'Rol ' . $role->name . ' is aangepast.');
    }

    public function destroy($id)
    {
        $this->authorize('delete-role');

        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('chief.back.roles.index')
            ->with('messages.success', 'Rol ' . $role->name . ' is verwijderd.');
    }
}
