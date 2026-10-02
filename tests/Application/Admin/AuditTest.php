<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Admin;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\Admin\Authorization\AuthorizationDefaults;
use Thinktomorrow\Chief\Admin\Authorization\Permission;
use Thinktomorrow\Chief\Plugins\Audit\AuditEventDTO;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\RecordAuditableEvent;
use Thinktomorrow\Chief\Plugins\Audit\Tests\Fixtures\ProjectOrderApproved;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class AuditTest extends ChiefTestCase
{
    public function test_audit_is_disabled_without_the_plugin(): void
    {
        $this->assertArrayNotHasKey('chief-audit:permissions', Artisan::all());
        $this->assertNotContains('view-audit', AuthorizationDefaults::permissions()->all());
        $this->assertFalse(Permission::where('name', 'view-audit')->exists());

        $article = $this->setupAndCreateArticle();
        $article->getStateConfig('current_state')->emitEvent($article, 'archive', []);

        $this->assertCount(0, Audit::getAllActivityFor($article));
        $this->assertNull(History::log(type: 'project.test', actorType: 'system', actorSnapshot: ['name' => 'System']));
        $this->assertNull(app('router')->getRoutes()->getByName('chief.audit.index'));
        $this->asAdmin()->get('/admin/audit')->assertNotFound();
    }

    public function test_chief_legacy_writes_do_not_disable_project_spatie_logging(): void
    {
        Audit::activity()->log('Ignored Chief event');

        activity()->log('Project event');

        $this->assertSame(['Project event'], Audit::query()->pluck('description')->all());
    }

    public function test_explicit_audit_event_listener_is_inert_without_the_plugin(): void
    {
        $event = new ProjectOrderApproved(new AuditEventDTO(
            type: 'project.order.approved', actorType: 'system', actorSnapshot: ['name' => 'Scheduler'],
        ));

        Event::listen(ProjectOrderApproved::class, RecordAuditableEvent::class);

        $this->assertNull(History::logEvent($event));
        Event::dispatch($event);
        $this->assertFalse(Schema::hasTable('chief_audit_events'));
    }
}
