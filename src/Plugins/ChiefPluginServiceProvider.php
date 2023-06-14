<?php

namespace Thinktomorrow\Chief\Plugins;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Shared\AdminEnvironment;

abstract class ChiefPluginServiceProvider extends ServiceProvider
{
    protected function isRequestInAdminEnvironment()
    {
        return $this->app->make(AdminEnvironment::class)->check(request());
    }

    protected function loadPluginAdminRoutes(string $path): void
    {
        if(! $this->isRequestInAdminEnvironment()) {
            return;
        }

        Route::group(['prefix' => config('chief.route.prefix', 'admin'), 'middleware' => ['web-chief', 'auth:chief']], function () use ($path) {
            $this->loadRoutesFrom($path);
        });
    }

    public function register()
    {
        parent::register();

        $this->app->singleton(ChiefPluginSections::class, fn () => new ChiefPluginSections());
    }
}
