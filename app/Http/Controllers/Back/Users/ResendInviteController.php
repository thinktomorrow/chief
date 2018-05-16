<?php

namespace App\Http\Controllers\Back\Users;

use App\Http\Controllers\Controller;
use Chief\Authorization\Role;
use Chief\Users\Invites\Application\AcceptInvite;
use Chief\Users\Invites\Application\InviteUser;
use Chief\Users\Invites\Invitation;
use Chief\Users\User;
use Illuminate\Http\Request;

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
