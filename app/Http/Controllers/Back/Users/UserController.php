<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Users;

use Thinktomorrow\Chief\Users\User;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Authorization\Role;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Users\Invites\Application\InviteUser;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('view-user');

        $users = User::all();
        return view('chief::back.users.index')->with('users', $users);
    }

    /**
     * Show the invite form
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create-user');

        return view('chief::back.users.create', [
            'user'      => new User(),
            'roleNames' => Role::all()->pluck('name')->toArray()
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create-user');

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' =>  'required|email|unique:'.(new User())->getTable(),
            'roles' => 'required|array',
        ]);

        $user = User::create(
            $request->only(['firstname', 'lastname', 'email'])
        );

        $user->assignRole($request->get('roles', []));

        app(InviteUser::class)->handle($user, auth()->guard('chief')->user());

        return redirect()->route('chief.back.users.index')
            ->with('messages.success', 'De nieuwe gebruiker is uitgenodigd en zal zodra een bevestiging ontvangen via mail.');
    }

    public function edit($id)
    {
        $this->authorize('update-user');

        if (auth()->guard('chief')->user()->hasRole('developer')) {
            $roles = Role::all();
        } else {
            $roles = Role::whereNotIn('name', ['developer'])->get();
        }

        return view('chief::back.users.edit', [
            'user'      => User::findOrFail($id),
            'roleNames' => $roles->pluck('name')->toArray()
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update-user');

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' =>  'required|email|unique:'.(new User())->getTable().',email,'.$id,
            'roles' => 'required|array',
        ]);

        $user = User::findOrFail($id);

        $user->update($request->only(['firstname', 'lastname', 'email']));
        $user->syncRoles($request->get('roles', []));

        return redirect()->route('chief.back.users.index')
            ->with('messages.success', 'Gegevens van de gebruiker zijn aangepast.');
    }
}
