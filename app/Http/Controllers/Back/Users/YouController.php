<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Users;

use Thinktomorrow\Chief\Users\User;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Authorization\Role;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Users\Invites\Application\InviteUser;

class YouController extends Controller
{
    public function edit()
    {
        return view('chief::back.you.edit', [
            'user' => admin(),
        ]);
    }

    public function update(Request $request)
    {
        $user = admin();

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' =>  'required|email|unique:'.(new User())->getTable().',email,'.$user->id,
        ]);

        $user->update($request->only(['firstname', 'lastname', 'email']));

        return redirect()->back()
            ->with('messages.success', 'Jouw profiel is aangepast.');
    }
}
