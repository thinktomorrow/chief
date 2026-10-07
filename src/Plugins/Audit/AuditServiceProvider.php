<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Admin\Authentication\Events\ChiefLoginCompleted;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\UserInvited;
use Thinktomorrow\Chief\ManagedModels\Events\ChiefActionCompleted;
use Thinktomorrow\Chief\Plugins\Audit\App\Commands\AuditPermissionsCommand;
use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;
use Thinktomorrow\Chief\Plugins\Export\Events\ChiefExportCompleted;

final class AuditServiceProvider extends ChiefPluginServiceProvider
{
    public const PERMISSIONS = ['view-full-audit', 'view-related-audit'];

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
        Event::listen(ChiefActionCompleted::class, [RecordChiefAction::class, 'handle']);
        Event::listen(ChiefLoginCompleted::class, [RecordChiefLogin::class, 'handle']);
        Event::listen(UserInvited::class, [RecordUserInvitation::class, 'handle']);
        Event::listen(ChiefExportCompleted::class, [RecordChiefExport::class, 'handle']);

        $this->commands([AuditPermissionsCommand::class]);

        $this->loadMigrationsFrom(__DIR__.'/Infrastructure/migrations');
        $this->app['view']->addNamespace('chief-audit', __DIR__.'/UI/views');
        $this->loadPluginAdminRoutes(__DIR__.'/App/routes/chief-admin-routes.php');
    }
}
