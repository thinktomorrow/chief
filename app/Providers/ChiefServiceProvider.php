<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Thinktomorrow\Chief\App\Console\CreateAdmin;
use Thinktomorrow\Chief\App\Console\RefreshDatabase;
use Thinktomorrow\Chief\App\Exceptions\Handler;
use Thinktomorrow\Chief\Authorization\Console\GeneratePermissionCommand;
use Thinktomorrow\Chief\Authorization\Console\GenerateRoleCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Users\User;

class ChiefServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register the Chief Exception handler
        $this->app->singleton(
            ExceptionHandler::class,
            Handler::class
        );

        $this->registerChiefGuard();

        (new AuthServiceProvider($this->app))->boot();
        (new EventServiceProvider($this->app))->boot();

        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'chief');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                GeneratePermissionCommand::class,
                GenerateRoleCommand::class,
                CreateAdmin::class,
                RefreshDatabase::class,
            ]);
        }

        Blade::component('chief.back._layouts._partials.header', 'chiefheader');
        Blade::component('chief.back._elements.formgroup', 'chiefformgroup');
    }

    public function register()
    {
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
            'table'    => 'password_resets',
            'expire'   => 60,
        ];
    }
}
