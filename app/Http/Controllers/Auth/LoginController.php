<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => 'logout']);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember))
        {
            return redirect()->intended(route('back.dashboard'));
        }

        $failedAttempt = 'Uw gegevens zijn onjuist of uw account is nog niet actief.';

        return redirect()->back()->withInput($request->only('email','remember'))->withErrors($failedAttempt);
    }

    /**
     * Log the admin out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}
