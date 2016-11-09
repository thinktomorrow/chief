<?php

namespace Chief\SquantoManager;

use Illuminate\Support\ServiceProvider;

class SquantoManagerServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/routes/web.php';
        }

        // Register squanto viewfiles under squanto:: namespace
        view()->addNamespace('squanto',realpath(__DIR__.'/views'));
    }

    /**
     * Register our translator
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
