<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Users;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Authorization\Role;
use Thinktomorrow\Chief\Users\Invites\Application\AcceptInvite;
use Thinktomorrow\Chief\Users\Invites\Application\DenyInvite;
use Thinktomorrow\Chief\Users\Invites\Application\InviteUser;
use Thinktomorrow\Chief\Users\Invites\Invitation;
use Thinktomorrow\Chief\Users\User;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['validate-invite'])->except('expired');
    }

    public function expired()
    {
        return view('chief::back.users.invite-expired');
    }

    public function accept(Request $request)
    {
        $invitation = Invitation::findByToken($request->token);

        app(AcceptInvite::class)->handle($invitation);

        // Log user into the system and proceed to start page
        auth()->guard('chief')->login($invitation->invitee);

        if (is_null($invitation->invitee->password))
        {
            return redirect()->route('chief.back.password.edit');
        }

        return redirect()->route('chief.back.dashboard.getting-started');
    }

    public function deny(Request $request)
    {
        $invitation = Invitation::findByToken($request->token);

        app(DenyInvite::class)->handle($invitation);

        return view('chief::back.users.invite-denied');
    }

}
