<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Auth;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\Admin\Authentication\ChiefLogoutService;
use Thinktomorrow\Chief\Admin\Authentication\Events\ChiefLoginCompleted;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('chief-guest', ['except' => 'logout']);
    }

    /**
     * @return Factory|View
     */
    public function showLoginForm()
    {
        return view('chief::admin.auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('chief')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $admin = Auth::guard('chief')->user();
            if ($admin instanceof User) {
                event(ChiefLoginCompleted::succeeded($admin));
            }

            return redirect()->intended(route('chief.back.dashboard'));
        }

        event(ChiefLoginCompleted::failed($request->email));

        $failedAttempt = ['email' => 'Jouw gegevens zijn onjuist of jouw account is nog niet actief.'];

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors($failedAttempt);
    }

    /**
     * Log the admin out of the application.
     *
     *
     * @return RedirectResponse|Redirector
     */
    public function logout(Request $request, ChiefLogoutService $logoutService)
    {
        $logoutService->logout($request);

        return redirect('/');
    }
}
