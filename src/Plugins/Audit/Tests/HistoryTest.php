<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class HistoryTest extends ChiefTestCase
{
    protected function getPackageProviders($app)
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_it_records_supplied_historical_context_and_shows_it_to_an_authorized_admin(): void
    {
        $actor = $this->admin();
        $article = $this->setupAndCreateArticle();
        $originalName = $actor->fullname;

        History::log([
            'type' => 'project.article.approved',
            'category' => 'content',
            'outcome' => 'success',
            'occurred_at' => '2026-09-01 12:30:00',
            'summary' => 'Article approved',
            'actor_type' => 'admin',
            'actor_snapshot' => ['id' => (string) $actor->id, 'name' => $originalName],
            'model_type' => $article->getMorphClass(),
            'model_id' => (string) $article->getKey(),
            'model_snapshot' => ['name' => 'Original article'],
        ]);

        $article->getStateConfig('current_state')->emitEvent($article, 'archive', []);

        $actor->update(['firstname' => 'Changed']);
        $article->delete();

        $this->assertDatabaseHas('chief_audit_events', [
            'type' => 'project.article.approved',
            'category' => 'content',
            'outcome' => 'success',
            'occurred_at' => '2026-09-01 12:30:00',
        ]);
        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertCount(0, Audit::getAllActivityFor($article));

        $viewer = $this->admin();
        $viewer->givePermissionTo('view-full-audit');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()
            ->assertSee('Article approved')
            ->assertSee($originalName)
            ->assertSee('Original article')
            ->assertDontSee('Changed');
    }

    public function test_registration_joins_the_callers_transaction(): void
    {
        DB::beginTransaction();

        History::log([
            'type' => 'project.export', 'category' => 'system', 'summary' => 'Exported',
            'actor_type' => 'system', 'actor_snapshot' => ['name' => 'Scheduler'],
        ]);

        DB::rollBack();

        $this->assertDatabaseCount('chief_audit_events', 0);
    }

    public function test_old_audit_permission_does_not_grant_access_to_the_new_timeline(): void
    {
        History::log([
            'type' => 'project.export', 'category' => 'system', 'summary' => 'Private export',
            'actor_type' => 'system', 'actor_snapshot' => ['name' => 'Scheduler'],
        ]);

        $this->actingAs($this->admin(), 'chief')->get(route('chief.audit.index'))
            ->assertRedirect(route('chief.back.dashboard'));

        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-full-audit');
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertSee('Private export');
    }

    public function test_only_explicit_snapshot_fields_are_accepted(): void
    {
        $this->expectException(ValidationException::class);

        History::log([
            'type' => 'project.export', 'category' => 'system', 'summary' => 'Export',
            'actor_type' => 'system', 'actor_snapshot' => ['name' => 'Scheduler', 'password' => 'secret'],
        ]);
    }

    public function test_summary_is_escaped_in_the_timeline(): void
    {
        History::log([
            'type' => 'project.export', 'category' => 'system', 'summary' => '<script>alert(1)</script>',
            'actor_type' => 'system', 'actor_snapshot' => ['name' => 'Scheduler'],
        ]);

        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-full-audit');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }
}
