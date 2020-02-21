<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Users;

use Thinktomorrow\Chief\Users\User;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class YouController extends Controller
{
    public function edit()
    {
        return view('chief::back.you.edit', [
            'user' => chiefAdmin(),
        ]);
    }

    public function update(Request $request)
    {
        $user = chiefAdmin();

        $this->validate($request, [
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'email' =>  'required|email|unique:'.(new User())->getTable().',email,'.$user->id,
        ]);

        $user->update($request->only(['firstname', 'lastname', 'email']));

        return redirect()->back()
            ->with('messages.success', 'Jouw profiel is aangepast.');
    }
}
