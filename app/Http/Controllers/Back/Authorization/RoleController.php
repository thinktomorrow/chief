<?php

namespace App\Http\Controllers\Back\Authorization;

use App\Http\Controllers\Controller;
use Chief\Authorization\Permission;
use Chief\Authorization\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('back.roles.index')->with('roles', $roles);
    }

    public function create()
    {
        $this->authorize('create-role');

        $permissions = Permission::all();
        return view('back.authorization.roles.create', ['permissions'=>$permissions]);
    }

    public function store(Request $request)
    {
        $this->authorize('create-role');

        $this->validate($request, [
            'name'               => 'required|unique:roles',
            'permission_names'   => 'required|array',
        ]);

        $role = Role::create($request->only('name'));
        $role->givePermissionTo($request->permission_names);

        return redirect()->route('back.roles.index')
                         ->with('messages.success', 'Rol '. $role->name.' is toegevoegd.');
    }

    public function edit($id)
    {
        $this->authorize('update-role');

        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('back.authorization.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update-role');

        $this->validate($request, [
            'name' => 'required|unique:roles,name,'.$id,
            'permission_names' =>'required|array',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permission_names);

        return redirect()->route('back.roles.index')
            ->with('messages.success', 'Rol '. $role->name.' is aangepast.');
    }

    public function destroy($id)
    {
        $this->authorize('delete-role');

        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('back.roles.index')
            ->with('messages.success', 'Rol '. $role->name.' is verwijderd.');
    }
}
