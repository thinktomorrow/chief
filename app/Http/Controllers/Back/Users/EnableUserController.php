<?php

namespace App\Http\Controllers\Back\Users;

use Chief\Users\Application\EnableUser;
use Chief\Users\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chief\Users\Application\DisableUser;

class EnableUserController extends Controller
{
    public function store(Request $request, $id)
    {
        $this->authorize('create-user');

        $user = User::findOrFail($id);

        app(EnableUser::class)->handle($user);

        return redirect()->route('back.users.index')
            ->with('messages.success', 'De gebruikersaccount is opnieuw toegang verleend.');
    }
}
