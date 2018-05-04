<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        Blade::component('back._layouts._partials.header', 'chiefheader');
        Blade::component('back._elements.formgroup', 'chiefformgroup');
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
