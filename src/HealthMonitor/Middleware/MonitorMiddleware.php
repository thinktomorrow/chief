<?php

namespace Thinktomorrow\Chief\HealthMonitor\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\HealthMonitor\Monitor;

class MonitorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('get')) {
            app(Monitor::class)->check();
        }

        return $next($request);
    }
}
