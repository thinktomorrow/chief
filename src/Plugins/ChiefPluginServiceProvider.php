<?php

namespace Thinktomorrow\Chief\Plugins;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Plugins\Docs\DocSources;
use Thinktomorrow\Chief\Shared\AdminEnvironment;

abstract class ChiefPluginServiceProvider extends ServiceProvider
{
    protected function isRequestInAdminEnvironment()
    {
        return $this->app->make(AdminEnvironment::class)->check(request());
    }

    protected function loadPluginAdminRoutes(string $path): void
    {
        if (! $this->isRequestInAdminEnvironment()) {
            return;
        }

        Route::group(['prefix' => config('chief.route.prefix', 'admin'), 'middleware' => ['web-chief', 'auth:chief']], function () use ($path) {
            $this->loadRoutesFrom($path);
        });
    }

    /**
     * Add the plugin's guides to the user guide under the given namespace.
     */
    protected function loadDocsFrom(string $path, string $namespace): void
    {
        $this->callAfterResolving(DocSources::class, fn (DocSources $sources) => $sources->add($namespace, $path));
    }

    public function register()
    {
        parent::register();

        $this->app->singleton(ChiefPluginSections::class, fn () => new ChiefPluginSections);
    }
}
