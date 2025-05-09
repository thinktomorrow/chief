<?php

namespace Thinktomorrow\Chief\App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (Auth::guest()) {
            return redirect('/');
        }

        if (! $request->user('chief')->can($permission)) {
            abort(403);
        }

        return $next($request);
    }
}
