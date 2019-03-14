<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Users;

use Illuminate\Auth\Access\AuthorizationException;
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
            'roleNames' => Role::rolesForSelect(chiefAdmin()->hasRole('developer')),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create-user');

        // Sanitize an empty array that is passed as [null]
        $requestRoles = $request->get('roles');
        if(is_array($requestRoles) && count($requestRoles) == 1 && reset($requestRoles) === null ){
            $request = $request->merge(['roles' => []]);
        }

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

        return view('chief::back.users.edit', [
            'user'      => User::findOrFail($id),
            'roleNames' => Role::rolesForSelect(chiefAdmin()->hasRole('developer'))
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update-user');

        // Sanitize an empty array that is passed as [null]
        $requestRoles = $request->get('roles');
        if(is_array($requestRoles) && count($requestRoles) == 1 && reset($requestRoles) === null ){
            $request = $request->merge(['roles' => []]);
        }

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' =>  'required|email|unique:'.(new User())->getTable().',email,'.$id,
            'roles' => 'required|array',
        ]);

        $user = User::findOrFail($id);

        // Only another developer can change another developer.
        if(!chiefAdmin()->hasRole('developer') && ($user->hasRole('developer') || in_array('developer', $request->get('roles',[])) )) {
            throw new AuthorizationException('Constraint: Only an user with role developer can update an user with developer role.');
        }

        $user->update($request->only(['firstname', 'lastname', 'email']));
        $user->syncRoles($request->get('roles', []));

        return redirect()->route('chief.back.users.index')
            ->with('messages.success', 'Gegevens van de gebruiker zijn aangepast.');
    }
}
