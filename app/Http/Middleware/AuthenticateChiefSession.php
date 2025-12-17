<?php

namespace Thinktomorrow\Chief\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Thinktomorrow\Chief\Admin\Authentication\ChiefLogoutService;
use Thinktomorrow\Chief\Admin\Users\User;

class AuthenticateChiefSession
{
    public function handle($request, Closure $next)
    {
        if (! $request->hasSession()) {
            return $next($request);
        }

        /** @var User|null $admin */
        $admin = $request->user('chief');

        if (! $admin) {
            return $next($request);
        }

        if (! $admin->isEnabled()) {
            return $this->logoutAndRedirect($request);
        }

        if (! $this->getPasswordHashFromSession($request)) {
            $this->storePasswordHashInSession($request, $admin);
        }

        if ($this->getPasswordHashFromSession($request) !== $admin->getAuthPassword()) {
            return $this->logoutAndRedirect($request);
        }

        return tap($next($request), function () use ($request, $admin) {
            $this->storePasswordHashInSession($request, $admin);
        });
    }

    protected function storePasswordHashInSession(Request $request, User $admin): void
    {
        $currentHash = $this->getPasswordHashFromSession($request);
        $actualHash = $admin->getAuthPassword();

        if ($currentHash !== $actualHash) {
            $request->session()->put('chief_password_hash', $actualHash);
        }
    }

    private function logoutAndRedirect(Request $request): Response
    {
        app(ChiefLogoutService::class)->logout($request);

        // JSON callers correct afhandelen
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('/admin/login');
    }

    private function getPasswordHashFromSession(Request $request): ?string
    {
        return $request->session()->get('chief_password_hash');
    }
}
