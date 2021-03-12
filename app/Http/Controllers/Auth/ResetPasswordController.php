<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Thinktomorrow\Chief\Admin\Authorization\ChiefPasswordBroker;
use Thinktomorrow\Chief\Admin\Authorization\ChiefPasswordBrokerResolver;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    public function __construct()
    {
        $this->middleware('chief-guest');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('chief::auth.passwords.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    /**
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('chief');
    }

    protected function broker()
    {
        return (new ChiefPasswordBrokerResolver(app()))->resolve();
    }

    public function redirectTo(): string
    {
        return route('chief.back.dashboard');
    }

    // Override the reset method because chief uses different lang keys and
    // laravel internals expects this to be of a specific value.
    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset($this->credentials($request),            function ($user, $password) {
                $this->resetPassword($user, $password);
            });

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == ChiefPasswordBroker::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }
}
