<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Livewire\LivewireServiceProvider;
use Spatie\Sitemap\SitemapServiceProvider;
use Thinktomorrow\AssetLibrary\AssetLibraryServiceProvider;
use Thinktomorrow\Chief\Admin\Authorization\Console\GeneratePermissionCommand;
use Thinktomorrow\Chief\Admin\Authorization\Console\GenerateRoleCommand;
use Thinktomorrow\Chief\Admin\Nav\Nav;
use Thinktomorrow\Chief\Admin\Settings\SettingFields;
use Thinktomorrow\Chief\Admin\Settings\Settings;
use Thinktomorrow\Chief\Admin\Settings\SettingsServiceProvider;
use Thinktomorrow\Chief\Admin\Setup\CreateFragmentCommand;
use Thinktomorrow\Chief\Admin\Setup\CreatePageCommand;
use Thinktomorrow\Chief\Admin\Setup\CreatePageMigrationCommand;
use Thinktomorrow\Chief\Admin\Setup\FileManipulation;
use Thinktomorrow\Chief\Admin\Setup\SetupConfig;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\App\Console\CreateAdmin;
use Thinktomorrow\Chief\App\Console\CreateDeveloper;
use Thinktomorrow\Chief\App\Console\GenerateSitemap;
use Thinktomorrow\Chief\App\Console\RefreshDatabase;
use Thinktomorrow\Chief\App\Console\Seed;
use Thinktomorrow\Chief\App\Http\Controllers\Back\System\SettingsController;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Managers\Register\TaggedKeys;
use Thinktomorrow\Squanto\SquantoManagerServiceProvider;
use Thinktomorrow\Squanto\SquantoServiceProvider;

class ChiefServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerChiefGuard();
        $this->registerSquanto();

        (new MacrosServiceProvider($this->app))->boot();
        (new AuthServiceProvider($this->app))->boot();
        (new EventServiceProvider($this->app))->boot();
        (new ViewServiceProvider($this->app))->boot();
        (new ValidationServiceProvider($this->app))->boot();
        (new SquantoServiceProvider($this->app))->boot();
        (new SquantoManagerServiceProvider($this->app))->boot();
        (new SettingsServiceProvider($this->app))->boot();

        // Packages
        (new AssetLibraryServiceProvider($this->app))->boot();
        (new SitemapServiceProvider($this->app))->boot();
        (new LivewireServiceProvider($this->app))->boot();

        // Project defaults
        (new ChiefRoutesServiceProvider($this->app))->boot();

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'chief');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'chief');

        $this->publishes([
            __DIR__ . '/../../config/chief.php' => config_path('chief.php'),
            __DIR__ . '/../../config/chief-settings.php' => config_path('chief-settings.php'),
        ], 'chief-config');

        $this->publishes([
            __DIR__ . '/../../public/chief-assets' => public_path('/chief-assets'),
        ], 'chief-assets');

        // Commands for both cli and web scripts
        $this->commands([
            // Sitemap generation
            'command.chief:sitemap',
        ]);

        $this->app->bind('command.chief:sitemap', GenerateSitemap::class);

        // Commands for cli only
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
                'command.chief:page-migration',
                'command.chief:fragment',
            ]);

            // Bind our commands to the container
            $this->app->bind('command.chief:refresh', RefreshDatabase::class);
            $this->app->bind('command.chief:seed', Seed::class);
            $this->app->bind('command.chief:permission', GeneratePermissionCommand::class);
            $this->app->bind('command.chief:role', GenerateRoleCommand::class);

            $this->app->bind('command.chief:page', CreatePageCommand::class);
            $this->app->bind('command.chief:page-migration', CreatePageMigrationCommand::class);
            $this->app->bind('command.chief:fragment', CreateFragmentCommand::class);
            $this->app->bind('command.chief:admin', CreateAdmin::class);
            $this->app->bind('command.chief:developer', CreateDeveloper::class);

            // Register sitemap command
            $this->app->make(Schedule::class)->command('chief:sitemap')->dailyAt('03:00');
        }

        // Custom validator for requiring on translations only the fallback locale
        // this is called in the validation as required-fallback-locale
        Validator::extendImplicit('requiredFallbackLocale', function ($attribute, $value) {
            $fallbackLocale = config('app.fallback_locale');

            if (false !== strpos($attribute, 'trans.' . $fallbackLocale . '.')) {
                return ! ! trim($value);
            }

            return true;
        }, 'Voor :attribute is minstens de default taal verplicht in te vullen, aub.');

        Relation::morphMap([
            'fragmentmodel' => FragmentModel::class,
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/chief.php', 'chief');
        $this->mergeConfigFrom(__DIR__ . '/../../config/chief-settings.php', 'chief-settings');

        $this->setupEnvironmentProviders();

        $this->app->when(SettingsController::class)
            ->needs(SettingFields::class)
            ->give(function () {
                return new SettingFields(new Settings());
            });

        $this->app->singleton(Registry::class, function () {
            return new Registry([], [], new TaggedKeys());
        });

        // Global chief nav singleton
        $this->app->singleton(Nav::class, function () {
            return new Nav();
        });

        // Setup commands
        $this->app->bind(CreatePageCommand::class, function ($app) {
            return new CreatePageCommand($app->make(FileManipulation::class), new SetupConfig(config('chief.setup', [])));
        });

        $this->app->bind(CreateFragmentCommand::class, function ($app) {
            return new CreateFragmentCommand($app->make(FileManipulation::class), new SetupConfig(config('chief.setup', [])));
        });

        (new MacrosServiceProvider($this->app))->register();
        (new AuthServiceProvider($this->app))->register();
        (new EventServiceProvider($this->app))->register();
        (new ViewServiceProvider($this->app))->register();
        (new ValidationServiceProvider($this->app))->register();
        (new SquantoServiceProvider($this->app))->register();
        (new SquantoManagerServiceProvider($this->app))->register();
        (new SettingsServiceProvider($this->app))->register();

        // Packages
        (new LivewireServiceProvider($this->app))->register();
        (new AssetLibraryServiceProvider($this->app))->register();
        (new SitemapServiceProvider($this->app))->register();

        // Project defaults
        (new ChiefRoutesServiceProvider($this->app))->register();
    }

    /**
     * Conditionally loads providers for specific environments.
     *
     * The app()->register() will both trigger the register and boot
     * methods of the service provider
     *
     * @return void
     */
    private function setupEnvironmentProviders(): void
    {
        if (! $this->app->environment('production') && $services = config('app.providers-' . app()->environment(), false)) {
            foreach ($services as $service) {
                $this->app->register($service);
            }
        }
    }

    private function registerChiefGuard(): void
    {
        $this->app['config']["auth.guards.chief"] = [
            'driver' => 'session',
            'provider' => 'chief',
        ];

        $this->app['config']["auth.providers.chief"] = [
            'driver' => 'chief-eloquent',
            'model' => User::class,
        ];

        $this->app['config']["auth.passwords.chief"] = [
            'provider' => 'chief',
            'table' => 'chief_password_resets',
            'expire' => 60,
        ];

        // Custom models for permission
        $this->app['config']['permission.models'] = [
            'permission' => \Thinktomorrow\Chief\Admin\Authorization\Permission::class,
            'role' => \Thinktomorrow\Chief\Admin\Authorization\Role::class,
        ];
    }

    private function registerSquanto(): void
    {
        // Project specific squanto files
        $this->app['view']->addNamespace('squanto', __DIR__ . '/../../resources/views/vendor/squanto');

        // Chief squanto defaults
        $this->app['view']->addNamespace('squanto', base_path() . '/resources/views/vendor/thinktomorrow/chief/vendor/squanto');

        // Use the chief routing
        $this->app['config']['squanto.use_default_routes'] = false;
    }
}
