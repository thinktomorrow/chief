<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Admin;

use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class AuditTest extends ChiefTestCase
{
    public function test_audit_is_disabled_without_the_plugin(): void
    {
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
}
