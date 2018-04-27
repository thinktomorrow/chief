<?php

namespace App\Http\Controllers\Back\Authorization;

use App\Http\Controllers\Controller;
use Chief\Authorization\Permission;
use Chief\Authorization\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Check if authenticated admin has the proper permissions. If not
     * a redirect route is given back to direct the user to
     *
     * @param $permissions
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    protected function redirectIfCannot($permissions)
    {
        if(!$admin = admin()) return redirect()->route('back.dashboard');

        if(admin()->cant($permissions)){
            return redirect()->route('back.dashboard');
        }

        return false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('back.roles.index')->with('roles', $roles);
    }

    public function create()
    {
        if($redirect = $this->redirectIfCannot('create-role')) return $redirect;

        $permissions = Permission::all();
        return view('back.authorization.roles.create', ['permissions'=>$permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
                'name'          =>'required|unique:roles|max:10',
                'permissions'   =>'required',
            ]
        );
        $name = $request['name'];
        $role = new Role();
        $role->name = $name;
        $permissions = $request['permissions'];
        $role->save();
        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail();
            $role = Role::where('name', '=', $name)->first();
            $role->givePermissionTo($p);
        }
        return redirect()->route('back.roles.index')
                         ->with('flash_message', 'Role'. $role->name.' added!');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('roles');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('back.roles.edit', compact('role', 'permissions'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $this->validate($request, [
            'name'=>'required|max:10|unique:roles,name,'.$id,
            'permissions' =>'required',
        ]);
        $input = $request->except(['permissions']);
        $permissions = $request['permissions'];
        $role->fill($input)->save();
        $p_all = Permission::all();
        foreach ($p_all as $p) {
            $role->revokePermissionTo($p);
        }
        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form permission in db
            $role->givePermissionTo($p);
        }
        return redirect()->route('back.roles.index')
            ->with('flash_message',
                'Role'. $role->name.' updated!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('back.roles.index')
            ->with('flash_message',
                'Role deleted!');
    }
}
