<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Urls\ChiefResponse;
use Thinktomorrow\Chief\App\Http\Middleware\ChiefValidateInvite;
use Thinktomorrow\Chief\HealthMonitor\Middleware\MonitorMiddleware;
use Thinktomorrow\Chief\App\Http\Middleware\AuthenticateChiefSession;
use Thinktomorrow\Chief\App\Http\Middleware\ChiefRedirectIfAuthenticated;

class ChiefRoutesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadOpenAdminRoutes();
        $this->loadAdminRoutes();
        $this->autoloadAdminMiddleware();
        $this->autoloadFrontendRoute();
    }

    private function loadOpenAdminRoutes()
    {
        Route::group(['prefix' => config('thinktomorrow.chief.route.prefix', 'admin'), 'middleware' => ['web']], function () {
            $this->loadRoutesFrom(__DIR__.'/../../routes/chief-open-routes.php');
        });
    }

    private function loadAdminRoutes()
    {
        Route::group(['prefix' => config('thinktomorrow.chief.route.prefix', 'admin'), 'middleware' => ['web-chief', 'auth:chief']], function () {
            $this->loadRoutesFrom(__DIR__.'/../../routes/chief-admin-routes.php');

            // Add project specific chief routing...
            $projectChiefRoutePath = config('thinktomorrow.chief.route.admin-filepath', null);

            if ($projectChiefRoutePath && file_exists($projectChiefRoutePath)) {
                $this->loadRoutesFrom($projectChiefRoutePath);
            }
        });
    }

    private function autoloadFrontendRoute()
    {
        if (true !== config('thinktomorrow.chief.route.autoload')) {
            return;
        }

        app()->booted(function () {
            $routeName = config('thinktomorrow.chief.route.name');

            Route::get('{slug?}', function ($slug = '/') use ($routeName) {
                return ChiefResponse::fromSlug($slug);
            })->name($routeName)
                ->where('slug', '(.*)?')
                ->middleware('web');
        });
    }

    private function autoloadAdminMiddleware()
    {
        app(Router::class)->middlewareGroup('web-chief', [
            // The default laravel web middleware - except for the csrf token verification.
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            // Chief specific middleware
            AuthenticateChiefSession::class,
            MonitorMiddleware::class,
        ]);

        app(Router::class)->aliasMiddleware('chief-guest', ChiefRedirectIfAuthenticated::class);
        app(Router::class)->aliasMiddleware('chief-validate-invite', ChiefValidateInvite::class);
    }
}
