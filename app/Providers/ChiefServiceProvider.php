<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\App\Console\CreateAdmin;
use Thinktomorrow\Chief\Pages\Console\GeneratePage;
use Thinktomorrow\Chief\App\Console\RefreshDatabase;
use Thinktomorrow\Chief\App\Exceptions\Handler;
use Thinktomorrow\Chief\Authorization\Console\GeneratePermissionCommand;
use Thinktomorrow\Chief\Authorization\Console\GenerateRoleCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Users\User;

class ChiefServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerChiefGuard();

        (new AuthServiceProvider($this->app))->boot();
        (new EventServiceProvider($this->app))->boot();

        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'chief');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__.'/../../config/chief.php' => config_path('thinktomorrow/chief.php'),
        ], 'chief-config');

        $this->publishes([
            __DIR__.'/../../public/chief-assets' => public_path('/chief-assets'),
        ], 'chief-assets');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                // Local development
                'command.chief:refresh',

                // Project setup tools
                'command.chief:permission',
                'command.chief:role',
                'command.chief:admin',
                'command.chief:page',
            ]);

            // Bind our commands to the container
            $this->app->bind('command.chief:refresh', RefreshDatabase::class);
            $this->app->bind('command.chief:permission', GeneratePermissionCommand::class);
            $this->app->bind('command.chief:role', GenerateRoleCommand::class);
            $this->app->bind('command.chief:admin', CreateAdmin::class);
            $this->app->bind('command.chief:page', function($app){
                return new GeneratePage($app['files'], [
                    'base_path' => base_path()
                ]);
            });

        }

        Blade::component('chief::back._layouts._partials.header', 'chiefheader');
        Blade::component('chief::back._elements.formgroup', 'chiefformgroup');
    }

    public function register()
    {
        // TODO: test this logic...
        $this->mergeConfigFrom(__DIR__.'/../../config/chief.php' , 'thinktomorrow.chief');

        $this->setupEnvironmentProviders();

        (new AuthServiceProvider($this->app))->register();
        (new EventServiceProvider($this->app))->register();
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

    private function registerChiefGuard()
    {
        $this->app['config']["auth.guards.chief"] = [
            'driver'   => 'session',
            'provider' => 'chief',
        ];

        $this->app['config']["auth.providers.chief"] = [
            'driver' => 'chief-eloquent',
            'model'  => User::class,
        ];

        $this->app['config']["auth.passwords.chief"] = [
            'provider' => 'chief',
            'table'    => 'chief_password_resets',
            'expire'   => 60,
        ];
    }
}
