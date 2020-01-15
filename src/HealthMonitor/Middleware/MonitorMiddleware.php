<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\HealthMonitor\Middleware;

use Closure;
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
