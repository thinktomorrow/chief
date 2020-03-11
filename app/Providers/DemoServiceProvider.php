<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class DemoServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        $this->loadRoutesFrom(__DIR__ . '/../../src/Demo/Http/routes/demoRoutes.php');
        $this->loadViewsFrom(__DIR__ . '/../../src/Demo/views', 'demo');
    }
}
