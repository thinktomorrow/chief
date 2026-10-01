<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

final class AuditServiceProvider extends ChiefPluginServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->bind(AuditRecorder::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Infrastructure/migrations');
        $this->app['view']->addNamespace('chief-audit', __DIR__.'/UI/views');
        $this->loadPluginAdminRoutes(__DIR__.'/App/routes/chief-admin-routes.php');
    }
}
