<?php

namespace App\Http\Controllers\Back\Users;

use Chief\Users\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chief\Users\Application\DisableUser;

class DisableUserController extends Controller
{
    public function store(Request $request, $id)
    {
        $this->authorize('create-user');

        $user = User::findOrFail($id);

        app(DisableUser::class)->handle($user);

        return redirect()->route('back.users.index')
            ->with('messages.success', 'De gebruikersaccount is geblokkeerd met onmiddellijke ingang.');
    }
}
