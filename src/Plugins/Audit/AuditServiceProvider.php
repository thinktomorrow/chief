<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Thinktomorrow\Chief\Plugins\Audit\App\Commands\AuditPermissionsCommand;
use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

final class AuditServiceProvider extends ChiefPluginServiceProvider
{
    public const PERMISSIONS = ['view-audit', 'view-full-audit'];

    public function register(): void
    {
        parent::register();

        $this->app->bind(History::BINDING, History::class);

        $this->app['config']->set('chief.permissions.extra', array_unique(array_merge(
            config('chief.permissions.extra', []),
            self::PERMISSIONS
        )));
    }

    public function boot(): void
    {
        $this->commands([AuditPermissionsCommand::class]);

        $this->loadMigrationsFrom(__DIR__.'/Infrastructure/migrations');
        $this->app['view']->addNamespace('chief-audit', __DIR__.'/UI/views');
        $this->loadPluginAdminRoutes(__DIR__.'/App/routes/chief-admin-routes.php');
    }
}
