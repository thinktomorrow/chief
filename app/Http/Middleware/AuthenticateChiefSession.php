<?php

namespace Thinktomorrow\Chief\App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class AuthenticateChiefSession
{
    protected $auth;

    public function __construct()
    {
        $this->auth = Auth::guard('chief');
    }

    public function handle($request, Closure $next)
    {
        if (! $request->user('chief') || ! $request->session()) {
            return $next($request);
        }

        if ($this->auth->viaRemember()) {
            $passwordHash = explode('|', $request->cookies->get($this->auth->getRecallerName()))[2];

            if ($passwordHash != $request->user('chief')->getAuthPassword()) {
                $this->logout($request);
            }
        }

        if (! $request->session()->has('chief_password_hash')) {
            $this->storePasswordHashInSession($request);
        }

        if ($request->session()->get('chief_password_hash') !== $request->user('chief')->getAuthPassword()) {
            $this->logout($request);
        }

        return tap($next($request), function () use ($request) {
            $this->storePasswordHashInSession($request);
        });
    }

    /**
     * Store the user's current password hash in the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function storePasswordHashInSession($request)
    {
        if (! $request->user('chief')) {
            return;
        }

        $request->session()->put([
            'chief_password_hash' => $request->user('chief')->getAuthPassword(),
            ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function logout($request)
    {
        $this->auth->logout();

        $request->session()->remove('chief_password_hash');

        throw new AuthenticationException();
    }
}
