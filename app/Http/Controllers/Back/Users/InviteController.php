<?php

namespace App\Http\Controllers\Back\Users;

use App\Http\Controllers\Controller;
use Chief\Authorization\Role;
use Chief\Users\Invites\Application\AcceptInvite;
use Chief\Users\Invites\Application\DenyInvite;
use Chief\Users\Invites\Application\InviteUser;
use Chief\Users\Invites\Invitation;
use Chief\Users\User;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['validate-invite'])->except('expired');
    }

    public function expired()
    {
        return view('back.users.invite-expired');
    }

    public function accept(Request $request)
    {
        $invitation = Invitation::findByToken($request->token);

        app(AcceptInvite::class)->handle($invitation);

        // Log user into the system and proceed to start page
        auth()->guard('admin')->login($invitation->invitee);

        if (is_null($invitation->invitee->password))
        {
            return redirect()->route('back.password.edit');
        }

        return redirect()->route('back.getting-started');
    }

    public function deny(Request $request)
    {
        $invitation = Invitation::findByToken($request->token);

        app(DenyInvite::class)->handle($invitation);

        return view('back.users.invite-denied');
    }

}
