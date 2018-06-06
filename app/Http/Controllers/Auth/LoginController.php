<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Auth;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $failedAttempt = 'Uw gegevens zijn onjuist of uw account is nog niet actief.';

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

        $request->session()->invalidate();

        return redirect('/');
    }
}
