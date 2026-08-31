<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Users;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Admin\Users\Invites\Application\AcceptInvite;
use Thinktomorrow\Chief\Admin\Users\Invites\Application\DenyInvite;
use Thinktomorrow\Chief\Admin\Users\Invites\Invitation;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class InviteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['chief-validate-invite'])->except('expired');
    }

    /**
     * @return Factory|View
     */
    public function expired()
    {
        return view('chief::admin.users.invite-expired');
    }

    public function accept(Request $request)
    {
        $invitation = Invitation::findByToken($request->token);

        app(AcceptInvite::class)->handle($invitation);

        // Log user into the system and proceed to start page
        auth()->guard('chief')->login($invitation->invitee);

        if (is_null($invitation->invitee->password)) {
            return redirect()->route('chief.back.password.edit');
        }

        return redirect()->route('chief.back.dashboard.getting-started');
    }

    /**
     * @return Factory|View
     */
    public function deny(Request $request)
    {
        $invitation = Invitation::findByToken($request->token);

        app(DenyInvite::class)->handle($invitation);

        return view('chief::admin.users.invite-denied');
    }
}
