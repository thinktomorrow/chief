<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\HealthMonitor\Middleware;

use Closure;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Admin\HealthMonitor\Monitor;

class MonitorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
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
