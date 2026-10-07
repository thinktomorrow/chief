<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests\Recording;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Admin\Authorization\Role;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\Recording\AuditModelDTO;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class HistoryTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        Role::findByName('admin', 'chief')->givePermissionTo('view-audit');
    }

    protected function getPackageProviders($app)
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_it_records_supplied_historical_context_and_shows_it_to_an_authorized_admin(): void
    {
        $actor = $this->admin();
        $article = $this->setupAndCreateArticle();
        $originalName = $actor->fullname;

        History::log(
            type: 'project.article.approved',
            actorType: 'admin',
            actorSnapshot: ['id' => (string) $actor->id, 'name' => $originalName],
            category: 'content',
            outcome: 'success',
            occurredAt: '2026-09-01 12:30:00',
            summary: 'Article approved',
            models: [new AuditModelDTO(
                modelType: $article->getMorphClass(),
                modelId: (string) $article->getKey(),
                modelSnapshot: ['name' => 'Original article'],
            )],
        );

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
        $this->assertFalse(Schema::hasTable('activity_log'));

        $viewer = $this->admin();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');

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

        History::log(type: 'project.export', actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], summary: 'Exported');

        DB::rollBack();

        $this->assertDatabaseCount('chief_audit_events', 0);
    }

    public function test_related_permission_opens_a_neutral_index_without_exposing_full_history(): void
    {
        History::log(type: 'project.export', actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], summary: 'Private export');

        $this->actingAs($this->admin(), 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()
            ->assertSee('Geen historiek.')
            ->assertSee('href="'.route('chief.audit.index').'"', false)
            ->assertDontSee('Private export');

        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertSee('Private export');

        $viewer->givePermissionTo('view-audit');
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertSee('Private export');
    }

    public function test_only_explicit_snapshot_fields_are_accepted(): void
    {
        $this->expectException(InvalidArgumentException::class);

        History::log(type: 'project.export', actorType: 'system', actorSnapshot: ['name' => 'Scheduler', 'password' => 'secret']);
    }

    public function test_summary_is_escaped_in_the_timeline(): void
    {
        History::log(type: 'project.export', actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], summary: '<script>alert(1)</script>');

        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_type_alone_can_describe_an_event_with_a_general_category(): void
    {
        History::log(type: 'project.export', actorType: 'system', actorSnapshot: ['name' => 'Scheduler']);

        $this->assertDatabaseHas('chief_audit_events', [
            'type' => 'project.export', 'category' => 'general', 'summary' => null,
        ]);

        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertSee('project.export');
    }

    public function test_duplicate_model_links_are_rejected_before_the_event_is_saved(): void
    {
        $this->expectException(InvalidArgumentException::class);

        History::log(
            type: 'project.export',
            actorType: 'system',
            actorSnapshot: ['name' => 'Scheduler'],
            models: [
                new AuditModelDTO('article', '12', ['name' => 'Article']),
                new AuditModelDTO('article', '12', ['name' => 'Article again']),
            ],
        );
    }
}
