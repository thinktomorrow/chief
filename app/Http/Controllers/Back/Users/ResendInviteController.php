<?php

namespace App\Http\Controllers\Back\Users;

use Chief\Users\User;
use App\Http\Controllers\Controller;
use Chief\Users\Invites\Application\InviteUser;

class ResendInviteController extends Controller
{
    public function store($id)
    {
        $this->authorize('create-user');

        $user = User::findOrFail($id);

        app(InviteUser::class)->handle($user, auth()->guard('admin')->user());

        return redirect()->route('back.users.index')
            ->with('messages.success', $user->fullname. ' is opnieuw een uitnodiging verstuurd.');
    }

}
