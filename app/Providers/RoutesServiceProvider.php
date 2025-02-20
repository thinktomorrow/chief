<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Router;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Thinktomorrow\Chief\Admin\HealthMonitor\Middleware\MonitorMiddleware;
use Thinktomorrow\Chief\App\Http\Middleware\AuthenticateChiefSession;
use Thinktomorrow\Chief\App\Http\Middleware\ChiefAdminLocale;
use Thinktomorrow\Chief\App\Http\Middleware\ChiefNavigation;
use Thinktomorrow\Chief\App\Http\Middleware\ChiefRedirectIfAuthenticated;
use Thinktomorrow\Chief\App\Http\Middleware\ChiefValidateInvite;
use Thinktomorrow\Chief\App\Http\Middleware\EncryptCookies;
use Thinktomorrow\Chief\Shared\AdminEnvironment;
use Thinktomorrow\Chief\Site\Urls\ChiefResponse;

class RoutesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->autoloadFrontendRoute();
        $this->loadOpenAdminRoutes();

        if (app(AdminEnvironment::class)->check(request())) {
            $this->loadAdminRoutes();
            $this->autoloadAdminMiddleware();
        }
    }

    private function autoloadFrontendRoute()
    {
        if (config('chief.route.autoload') !== true) {
            return;
        }

        app()->booted(function () {
            $routeName = config('chief.route.name');

            Route::get('{slug?}', function ($slug = '/') {
                return ChiefResponse::fromSlug($slug);
            })->name($routeName)
                ->where('slug', '(.*)?')
                ->middleware('web');
        });
    }

    private function loadOpenAdminRoutes(): void
    {
        Route::group(['prefix' => config('chief.route.prefix', 'admin'), 'middleware' => ['web']], function () {
            $this->loadRoutesFrom(__DIR__.'/../../routes/chief-open-routes.php');
        });
    }

    private function loadAdminRoutes(): void
    {
        Route::group(['prefix' => config('chief.route.prefix', 'admin'), 'middleware' => ['web-chief', 'auth:chief']], function () {
            $this->loadRoutesFrom(__DIR__.'/../../routes/chief-admin-routes.php');

            // Add project specific chief routing...
            $projectChiefRoutePath = config('chief.route.admin-filepath', null);

            if ($projectChiefRoutePath && file_exists($projectChiefRoutePath)) {
                $this->loadRoutesFrom($projectChiefRoutePath);
            }
        });
    }

    private function autoloadAdminMiddleware(): void
    {
        app(Router::class)->middlewareGroup('web-chief', [
            // The default laravel web middleware - except for the csrf token verification.
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            SubstituteBindings::class,

            // Chief admin specific middleware
            AuthenticateChiefSession::class,
            MonitorMiddleware::class,
            ChiefNavigation::class,
            ChiefAdminLocale::class,
        ]);

        app(Router::class)->aliasMiddleware('chief-guest', ChiefRedirectIfAuthenticated::class);
        app(Router::class)->aliasMiddleware('chief-validate-invite', ChiefValidateInvite::class);
    }
}
