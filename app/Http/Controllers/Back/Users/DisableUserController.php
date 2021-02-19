<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Users;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Admin\Users\Application\DisableUser;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class DisableUserController extends Controller
{
    public function store(Request $request, $id)
    {
        $this->authorize('disable-user');

        if (chiefAdmin()->id == $id) {
            return redirect()->back()
                ->with('messages.error', 'U kan uzelf niet blokkeren.');
        }

        $user = User::findOrFail($id);

        app(DisableUser::class)->handle($user);

        return redirect()->route('chief.back.users.index')
            ->with('messages.success', 'De gebruikersaccount is geblokkeerd met onmiddellijke ingang.');
    }
}
