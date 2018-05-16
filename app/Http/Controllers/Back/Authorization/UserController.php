<?php

namespace App\Http\Controllers\Back\Authorization;

use App\Http\Controllers\Controller;
use Chief\Authorization\Role;
use Chief\Users\Invites\Application\InviteUser;
use Chief\Users\User;
use Illuminate\Http\Request;

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
            'roles'=>Role::all()
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
            ->with('messages.success', 'User successfully added.');
    }

    public function edit($id)
    {
        $this->authorize('update-user');

        $user = User::findOrFail($id);
        $roleNames = Role::all()->pluck('name')->toArray();

        return view('back.users.edit', compact('user', 'roleNames'));
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
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('back.users.index')
            ->with('flash_message',
                'User successfully deleted.');
    }

    public function publish($user, Request $request){
      $user = User::findOrFail($user);
      $user->status = ($request->publishAccount == 'on' ? 'Active' : 'Blocked');
      $user->save();

      return redirect()->back();

    }
}
