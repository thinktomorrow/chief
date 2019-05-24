<?php

namespace Thinktomorrow\Chief\App\Providers;

use Thinktomorrow\Chief\Users\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Thinktomorrow\Chief\App\Console\Seed;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\App\Console\CreateAdmin;
use Thinktomorrow\Squanto\SquantoServiceProvider;
use Thinktomorrow\Chief\Pages\Console\GeneratePage;
use Thinktomorrow\Chief\App\Console\CreateDeveloper;
use Thinktomorrow\Chief\App\Console\RefreshDatabase;
use Thinktomorrow\Chief\Settings\Console\SeedSettings;
use Thinktomorrow\Squanto\SquantoManagerServiceProvider;
use Thinktomorrow\Chief\Settings\SettingsServiceProvider;
use Thinktomorrow\AssetLibrary\AssetLibraryServiceProvider;
use Thinktomorrow\Chief\Authorization\Console\GenerateRoleCommand;
use Thinktomorrow\Chief\Authorization\Console\GeneratePermissionCommand;

class ChiefServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerChiefGuard();

        $this->app['view']->addNamespace('squanto', __DIR__ . '/../../resources/views/vendor/squanto');
        $this->app['view']->addNamespace('squanto', base_path() . '/resources/views/vendor/thinktomorrow/chief/vendor/squanto');

        (new MacrosServiceProvider($this->app))->boot();
        (new AuthServiceProvider($this->app))->boot();
        (new EventServiceProvider($this->app))->boot();
        (new SquantoServiceProvider($this->app))->boot();
        (new SquantoManagerServiceProvider($this->app))->boot();
        (new SettingsServiceProvider($this->app))->boot();

        (new AssetLibraryServiceProvider($this->app))->boot();

        // Project defaults
        (new ChiefProjectServiceProvider($this->app))->boot();

        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'chief');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'chief');

        $this->publishes([
            __DIR__ . '/../../config/chief.php' => config_path('thinktomorrow/chief.php'),
            __DIR__ . '/../../config/chief-settings.php' => config_path('thinktomorrow/chief-settings.php'),
        ], 'chief-config');

        $this->publishes([
            __DIR__ . '/../../public/chief-assets' => public_path('/chief-assets'),
        ], 'chief-assets');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                // Local development
                'command.chief:refresh',
                'command.chief:seed',

                // Project setup tools
                'command.chief:permission',
                'command.chief:role',
                'command.chief:admin',
                'command.chief:developer',
                'command.chief:page',
                'command.chief:settings',
            ]);

            // Bind our commands to the container
            $this->app->bind('command.chief:refresh', RefreshDatabase::class);
            $this->app->bind('command.chief:seed', Seed::class);
            $this->app->bind('command.chief:permission', GeneratePermissionCommand::class);
            $this->app->bind('command.chief:role', GenerateRoleCommand::class);
            $this->app->bind('command.chief:admin', CreateAdmin::class);
            $this->app->bind('command.chief:developer', CreateDeveloper::class);
            $this->app->bind('command.chief:settings', SeedSettings::class);
            $this->app->bind('command.chief:page', function ($app) {
                return new GeneratePage($app['files'], [
                    'base_path' => base_path()
                ]);
            });
        }

        Blade::component('chief::back._layouts._partials.header', 'chiefheader');
        Blade::component('chief::back._elements.formgroup', 'chiefformgroup');

        // Custom validator for requiring on translations only the fallback locale
        // this is called in the validation as required-fallback-locale
        Validator::extendImplicit('requiredFallbackLocale', function ($attribute, $value, $parameters, $validator) {
            $fallbackLocale = config('app.fallback_locale');

            if (false !== strpos($attribute, 'trans.' . $fallbackLocale . '.')) {
                return !!trim($value);
            }

            return true;
        }, 'Voor :attribute is minstens de default taal verplicht in te vullen, aub.');

        Validator::extendImplicit('image_required',
            'Thinktomorrow\Chief\Fields\ImageRequiredValidator@validate',
            'Foto :attribute is verplicht.');
        Validator::extend('chief_dimensions',
            'Thinktomorrow\Chief\Fields\MediaDimensionsValidator@validate',
            'Er was iets fout met de afmeting van de foto :attribute.');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/chief.php', 'thinktomorrow.chief');
        $this->mergeConfigFrom(__DIR__ . '/../../config/chief-settings.php', 'thinktomorrow.chief-settings');

        $this->setupEnvironmentProviders();

        // Manager register is globally available
        $this->app->singleton(Register::class, function () {
            return new Register();
        });

        (new MacrosServiceProvider($this->app))->register();
        (new AuthServiceProvider($this->app))->register();
        (new EventServiceProvider($this->app))->register();
        (new SquantoServiceProvider($this->app))->register();
        (new SquantoManagerServiceProvider($this->app))->register();
        (new SettingsServiceProvider($this->app))->register();

        (new AssetLibraryServiceProvider($this->app))->register();

        // Project defaults
        (new ChiefProjectServiceProvider($this->app))->register();
    }

    /**
     * Conditionally loads providers for specific environments.
     *
     * The app()->register() will both trigger the register and boot
     * methods of the service provider
     */
    private function setupEnvironmentProviders()
    {
        if (!$this->app->environment('production') && $services = config('app.providers-' . app()->environment(), false)) {
            foreach ($services as $service) {
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

        // Custom models for permission
        $this->app['config']['permission.models'] = [
            'permission' => \Thinktomorrow\Chief\Authorization\Permission::class,
            'role'       => \Thinktomorrow\Chief\Authorization\Role::class,
        ];
    }
}
