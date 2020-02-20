<?php

namespace Thinktomorrow\Chief\App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    public function handle($request, \Closure $next)
    {
        $adminRoute = config('thinktomorrow.chief.route.prefix', '/admin');

        // Add exception routes for all chief admin endpoints.
        $this->except[] = rtrim($adminRoute,'/').'/*';

        return parent::handle($request, $next);
    }
}
