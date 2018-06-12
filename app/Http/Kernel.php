<?php

namespace Thinktomorrow\Chief\App\Http;

use Thinktomorrow\Chief\App\Http\Middleware\AuthenticateChiefSession;
use Thinktomorrow\Chief\App\Http\Middleware\AuthenticateSuperadmin;
use Thinktomorrow\Chief\App\Http\Middleware\Honeypot;
use Thinktomorrow\Chief\App\Http\Middleware\OptimizeImages;
use Thinktomorrow\Chief\App\Http\Middleware\ChiefValidateInvite;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Thinktomorrow\Chief\App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Thinktomorrow\Chief\App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
//             \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Thinktomorrow\Chief\App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'web-chief' => [
            AuthenticateChiefSession::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'              => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic'        => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.superadmin'   => AuthenticateSuperadmin::class,
        'bindings'          => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can'               => \Illuminate\Auth\Middleware\Authorize::class,
        'throttle'          => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'role'              => \Thinktomorrow\Chief\App\Http\Middleware\RoleMiddleware::class,
        'permission'        => \Thinktomorrow\Chief\App\Http\Middleware\PermissionMiddleware::class,
        'optimizeImages'    => OptimizeImages::class,
        'honeypot'          => Honeypot::class,

        // TODO: should be replaced with proper role
        'squanto.developer' => \Thinktomorrow\Squanto\Manager\Http\Middleware\Developer::class,

        // Required chief middleware
        'chief-guest'             => \Thinktomorrow\Chief\App\Http\Middleware\ChiefRedirectIfAuthenticated::class,
        'chief-validate-invite'   => ChiefValidateInvite::class,
    ];
}
