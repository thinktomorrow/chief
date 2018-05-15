<?php

namespace App\Http\Controllers\Back\Authorization;

use App\Http\Controllers\Controller;
use Chief\Authorization\Role;
use Chief\Users\Invites\Application\AcceptInvite;
use Chief\Users\Invites\Invitation;
use Chief\Users\User;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['validate-invite']);
    }

    public function accept(Request $request)
    {
        $invitation = Invitation::findByToken($request->token);

        app(AcceptInvite::class)->handle($invitation);

        if(is_null($invitation->invitee->password)) {
            return redirect()->route('back.password.edit');
        }

        // Log user into the system and proceed to start page
        auth()->guard('admin')->login($invitation->invitee);

        return redirect()->route('back.getting-started');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('users');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::get();
        return view('back.users._partials.edituser', compact('user', 'roles'));
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
        $user = User::findOrFail($id);
        $this->validate($request, [
            'firstname'=>'required|max:120',
            'lastname'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id,
        ]);
        $input = $request->only(['firstname', 'lastname', 'email']);
        $roles = $request['roles'];

        $user->fill($input)->save();
        if (isset($roles)) {
            $user->roles()->sync($roles);
        }
        else {
            $user->roles()->detach();
        }
        return redirect()->route('back.users.index')
            ->with('flash_message',
                'User successfully edited.');
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
