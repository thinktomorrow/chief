<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        $user = auth()->guard('admin')->user();

        return view('auth.passwords.edit', ['new_password' => !$user->password]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6'
        ]);

        $user = auth()->guard('admin')->user();

        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        return redirect()->route('back.dashboard')->with('messages.success', 'Jouw wachtwoord is aangepast.');
    }
}
