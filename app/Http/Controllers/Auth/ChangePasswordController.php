<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        $user = auth()->guard('chief')->user();

        return view('chief::auth.passwords.edit', ['new_password' => ! $user->password]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);

        $user = auth()->guard('chief')->user();

        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        return redirect()->route('chief.back.dashboard')->with('messages.success', 'Jouw wachtwoord is aangepast.');
    }
}
