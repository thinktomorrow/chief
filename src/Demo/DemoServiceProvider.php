<?php

namespace Chief\Demo;

use Illuminate\Support\ServiceProvider;
use Thinktomorrow\AssetLibrary\Models\Asset;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

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
        $this->loadRoutesFrom(__DIR__ . '/Http/routes/demoRoutes.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'demo');
    }
}
