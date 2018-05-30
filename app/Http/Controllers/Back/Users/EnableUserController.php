<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Users;

use Thinktomorrow\Chief\Users\Application\EnableUser;
use Thinktomorrow\Chief\Users\User;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Users\Application\DisableUser;

class EnableUserController extends Controller
{
    public function store(Request $request, $id)
    {
        $this->authorize('disable-user');

        $user = User::findOrFail($id);

        app(EnableUser::class)->handle($user);

        return redirect()->route('chief.back.users.index')
            ->with('messages.success', 'De gebruikersaccount is opnieuw toegang verleend.');
    }
}
