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
        $this->setupDevProviders();
    }

    /**
     * Conditionally loads providers for non-production environments
     */
    private function setupDevProviders()
    {
        if (!$this->app->environment('production') && $services = config('app.dev-providers'))
        {
            foreach($services as $service)
            {
                $this->app->register($service);
            }
        }
    }
}
