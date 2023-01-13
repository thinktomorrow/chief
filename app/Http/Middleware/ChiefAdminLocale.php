<?php

namespace Thinktomorrow\Chief\App\Http\Middleware;

use Closure;

class ChiefAdminLocale
{
    public function handle($request, Closure $next)
    {
        if ($adminLocale = config('chief.admin_locale')) {
            app()->setLocale($adminLocale);
        }

        return $next($request);
    }
}
