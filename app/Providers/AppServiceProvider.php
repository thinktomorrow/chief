<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->setupEnvironmentProviders();
    }

    /**
     * Conditionally loads providers for specific environments.
     *
     * The app()->register() will both trigger the register and boot
     * methods of the service provider
     */
    private function setupEnvironmentProviders()
    {
        if (!$this->app->environment('production') && $services = config('app.providers-'.app()->environment(),false))
        {
            foreach($services as $service)
            {
                $this->app->register($service);
            }
        }
    }
}
