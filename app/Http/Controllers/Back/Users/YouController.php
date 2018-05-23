<?php

namespace App\Http\Controllers\Back\Users;

use Chief\Users\User;
use Illuminate\Http\Request;
use Chief\Authorization\Role;
use App\Http\Controllers\Controller;
use Chief\Users\Invites\Application\InviteUser;

class YouController extends Controller
{
    public function edit()
    {
        return view('back.you.edit',[
            'user' => admin(),
        ]);
    }

    public function update(Request $request)
    {
        $user = admin();

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' =>  'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update($request->only(['firstname', 'lastname', 'email']));

        return redirect()->back()
            ->with('messages.success', 'Jouw profiel is aangepast.');
    }
}
