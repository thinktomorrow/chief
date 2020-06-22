<?php

namespace Thinktomorrow\Chief\App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $permission)
    {
        if (Auth::guest()) {
            return redirect('/');
        }

        if (! $request->user('chief')->hasRole($role)) {
            abort(403);
        }

        if (! $request->user('chief')->can($permission)) {
            abort(403);
        }

        return $next($request);
    }
}
