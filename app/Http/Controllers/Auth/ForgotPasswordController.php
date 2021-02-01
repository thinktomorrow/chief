<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Auth;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Thinktomorrow\Chief\Admin\Authorization\ChiefPasswordBrokerResolver;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('chief-guest');
    }

    public function showLinkRequestForm()
    {
        return view('chief::auth.passwords.email');
    }

    protected function broker()
    {
        return (new ChiefPasswordBrokerResolver(app()))->resolve();
    }
}
