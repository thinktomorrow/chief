<?php

namespace App\Http\Controllers\Back\Users;

use Chief\Users\User;
use Illuminate\Http\Request;
use Chief\Authorization\Role;
use App\Http\Controllers\Controller;
use Chief\Users\Invites\Application\InviteUser;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('view-user');

        $users = User::all();
        return view('back.users.index')->with('users', $users);
    }

    /**
     * Show the invite form
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create-user');

        return view('back.users.create', [
            'user' => new User(),
            'roleNames'=> Role::all()->pluck('name')->toArray()
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create-user');

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' =>  'required|email|unique:users',
            'roles' => 'required|array',
        ]);

        $user = User::create(
            $request->only(['firstname', 'lastname', 'email'])
        );

        $user->assignRole($request->get('roles', []));

        app(InviteUser::class)->handle($user, auth()->guard('admin')->user());

        return redirect()->route('back.users.index')
            ->with('messages.success', 'De nieuwe gebruiker is uitgenodigd en zal zodra een bevestiging ontvangen via mail.');
    }

    public function edit($id)
    {
        $this->authorize('update-user');

        return view('back.users.edit',[
            'user' => User::findOrFail($id),
            'roleNames'=> Role::all()->pluck('name')->toArray()
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update-user');

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' =>  'required|email|unique:users,email,'.$id,
            'roles' => 'required|array',
        ]);

        $user = User::findOrFail($id);

        $user->update($request->only(['firstname', 'lastname', 'email']));
        $user->syncRoles($request->get('roles', []));

        return redirect()->route('back.users.index')
            ->with('messages.success', 'Gegevens van de gebruiker zijn aangepast.');
    }
}
