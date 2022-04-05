<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\App\Console\ProjectMenuCommand;
use Thinktomorrow\Chief\Admin\Authorization\Console\GeneratePermissionCommand;
use Thinktomorrow\Chief\Admin\Authorization\Console\GenerateRoleCommand;
use Thinktomorrow\Chief\Admin\Setup\CreateFragmentCommand;
use Thinktomorrow\Chief\Admin\Setup\CreatePageCommand;
use Thinktomorrow\Chief\Admin\Setup\CreatePageMigrationCommand;
use Thinktomorrow\Chief\Admin\Setup\CreateViewCommand;
use Thinktomorrow\Chief\Admin\Setup\FileManipulation;
use Thinktomorrow\Chief\Admin\Setup\SetupConfig;
use Thinktomorrow\Chief\App\Console\CreateAdmin;
use Thinktomorrow\Chief\App\Console\CreateDeveloper;
use Thinktomorrow\Chief\App\Console\RefreshDatabase;
use Thinktomorrow\Chief\App\Console\Seed;

class ConsoleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'chief');

        $this->publishes([
            __DIR__.'/../../config/chief.php' => config_path('chief.php'),
            __DIR__.'/../../config/chief-settings.php' => config_path('chief-settings.php'),
        ], 'chief-config');

        $this->publishes([
            __DIR__.'/../../public/chief-assets' => public_path('/chief-assets'),
        ], 'chief-assets');

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
            'command.chief:view',
            'command.chief:project-menu',
        ]);

        // Bind our commands to the container
        $this->app->bind('command.chief:refresh', RefreshDatabase::class);
        $this->app->bind('command.chief:seed', Seed::class);
        $this->app->bind('command.chief:permission', GeneratePermissionCommand::class);
        $this->app->bind('command.chief:role', GenerateRoleCommand::class);

        $this->app->bind('command.chief:page', CreatePageCommand::class);
        $this->app->bind('command.chief:page-migration', CreatePageMigrationCommand::class);
        $this->app->bind('command.chief:view', CreateViewCommand::class);
        $this->app->bind('command.chief:fragment', CreateFragmentCommand::class);
        $this->app->bind('command.chief:admin', CreateAdmin::class);
        $this->app->bind('command.chief:developer', CreateDeveloper::class);
        $this->app->bind('command.chief:project-menu', ProjectMenuCommand::class);
    }

    public function register()
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('chief:sitemap')->dailyAt('01:00');
        });

        // Setup commands
        $this->app->bind(CreatePageCommand::class, function ($app) {
            return new CreatePageCommand($app->make(FileManipulation::class), new SetupConfig(config('chief.setup', [])));
        });

        $this->app->bind(CreateFragmentCommand::class, function ($app) {
            return new CreateFragmentCommand($app->make(FileManipulation::class), new SetupConfig(config('chief.setup', [])));
        });
    }
}
