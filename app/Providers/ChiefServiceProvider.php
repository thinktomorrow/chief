<?php

namespace Thinktomorrow\Chief\App\Providers;

use Thinktomorrow\Chief\App\Console\CreateAdmin;
use Thinktomorrow\Chief\App\Console\RefreshDatabase;
use Chief\Authorization\Console\GeneratePermissionCommand;
use Chief\Authorization\Console\GenerateRoleCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ChiefServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::component('back._layouts._partials.header', 'chiefheader');
        Blade::component('back._elements.formgroup', 'chiefformgroup');

        (new AuthServiceProvider($this->app))->boot();
        (new EventServiceProvider($this->app))->boot();

        $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                GeneratePermissionCommand::class,
                GenerateRoleCommand::class,
                CreateAdmin::class,
                RefreshDatabase::class,
            ]);
        }
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
}
