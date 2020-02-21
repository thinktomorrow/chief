<?php

namespace Thinktomorrow\Chief\App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ChiefRedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard ?? 'chief')->check()) {
            return redirect('/admin');
        }

        return $next($request);
    }
}
