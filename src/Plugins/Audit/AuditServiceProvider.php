<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Admin\Authentication\Events\ChiefLoginCompleted;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\UserInvited;
use Thinktomorrow\Chief\ManagedModels\Events\ChiefActionCompleted;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\Plugins\Audit\App\Actions\RecordChiefAction;
use Thinktomorrow\Chief\Plugins\Audit\App\Actions\RecordChiefExport;
use Thinktomorrow\Chief\Plugins\Audit\App\Actions\RecordChiefLogin;
use Thinktomorrow\Chief\Plugins\Audit\App\Actions\RecordManagedModel;
use Thinktomorrow\Chief\Plugins\Audit\App\Actions\RecordSentInvitationMail;
use Thinktomorrow\Chief\Plugins\Audit\App\Actions\RecordUserInvitation;
use Thinktomorrow\Chief\Plugins\Audit\App\Commands\AuditPermissionsCommand;
use Thinktomorrow\Chief\Plugins\Audit\App\Commands\CleanupAuditCommand;
use Thinktomorrow\Chief\Plugins\Audit\App\Commands\ImportSpatieActivitiesCommand;
use Thinktomorrow\Chief\Plugins\Audit\Presentation\AuditPresentations;
use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;
use Thinktomorrow\Chief\Plugins\Export\Events\ChiefExportCompleted;

final class AuditServiceProvider extends ChiefPluginServiceProvider
{
    public const PERMISSIONS = ['view-full-audit', 'view-related-audit'];

    public function register(): void
    {
        parent::register();

        $this->mergeConfigFrom(__DIR__.'/config.php', 'chief-audit');

        $this->app->bind(History::BINDING, History::class);
        $this->app->singleton(AuditPresentations::class);

        $this->app['config']->set('chief.permissions.extra', array_unique(array_merge(
            config('chief.permissions.extra', []),
            self::PERMISSIONS
        )));
    }

    public function boot(): void
    {
        Event::listen(ChiefActionCompleted::class, [RecordChiefAction::class, 'handle']);
        Event::listen(ManagedModelCreated::class, [RecordManagedModel::class, 'created']);
        Event::listen(ManagedModelUpdated::class, [RecordManagedModel::class, 'updated']);
        Event::listen(ChiefLoginCompleted::class, [RecordChiefLogin::class, 'handle']);
        Event::listen(UserInvited::class, [RecordUserInvitation::class, 'handle']);
        Event::listen(MessageSent::class, [RecordSentInvitationMail::class, 'handle']);
        Event::listen(ChiefExportCompleted::class, [RecordChiefExport::class, 'handle']);

        $this->commands([AuditPermissionsCommand::class, ImportSpatieActivitiesCommand::class, CleanupAuditCommand::class]);

        $this->loadMigrationsFrom(__DIR__.'/Infrastructure/migrations');
        $this->app['view']->addNamespace('chief-audit', __DIR__.'/UI/views');
        $this->loadPluginAdminRoutes(__DIR__.'/App/routes/chief-admin-routes.php');
    }
}
