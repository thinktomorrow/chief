<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('chief-guest', ['except' => 'logout']);
    }

    public function showLoginForm()
    {
        return view('chief::auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('chief')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(route('chief.back.dashboard'));
        }

        $failedAttempt = 'Jouw gegevens zijn onjuist of jouw account is nog niet actief.';

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors($failedAttempt);
    }

    /**
     * Log the admin out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard('chief')->logout();

        $request->session()->forget('chief_password_hash');

        return redirect('/');
    }
}
