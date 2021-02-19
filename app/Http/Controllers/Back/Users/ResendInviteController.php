<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Users;

use Thinktomorrow\Chief\Admin\Users\Invites\Application\InviteUser;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class ResendInviteController extends Controller
{
    public function store($id)
    {
        $this->authorize('create-user');

        $user = User::findOrFail($id);

        app(InviteUser::class)->handle($user, auth()->guard('chief')->user());

        return redirect()->route('chief.back.users.index')
            ->with('messages.success', $user->fullname . ' is opnieuw een uitnodiging verstuurd.');
    }
}
