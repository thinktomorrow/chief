<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use stdClass;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Plugins\Audit\AuditModelDTO;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class ProjectHistoryTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_project_can_register_model_free_system_and_external_events_with_supplied_context(): void
    {
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);

        $this->travelTo('2026-09-02 09:00:00');

        History::log(
            type: 'project.export',
            actorType: 'system',
            actorSnapshot: ['name' => 'Scheduler'],
            occurredAt: '2026-09-01 12:30:00',
            outcome: 'success',
            context: ['source' => 'nightly'],
        );

        History::log(
            type: 'project.webhook.failed',
            actorType: 'external',
            actorSnapshot: ['name' => 'External service', 'id' => 'partner-42'],
            outcome: 'failure',
        );

        $this->assertDatabaseCount('chief_audit_events', 2);
        $export = DB::table('chief_audit_events')->where('type', 'project.export')->first();
        $this->assertSame('2026-09-01 12:30:00', $export->occurred_at);
        $this->assertSame('2026-09-02 09:00:00', $export->recorded_at);
        $this->assertSame(['source' => 'nightly'], json_decode($export->context, true));
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'project.webhook.failed', 'actor_type' => 'external', 'outcome' => 'failure']);
        $this->assertSame('2026-09-02 09:00:00', DB::table('chief_audit_events')->where('type', 'project.webhook.failed')->value('occurred_at'));

        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertSee('project.export')->assertSee('project.webhook.failed');
    }

    public function test_one_event_keeps_the_supplied_primary_and_related_model_snapshots_and_context(): void
    {
        $actor = ['name' => 'Operator', 'id' => '7'];
        $orderSnapshot = ['name' => 'Original order'];
        $models = [
            new AuditModelDTO('project.article', '12', ['name' => 'Original article'], ['action' => 'reviewed']),
            new AuditModelDTO('project.order', '42', $orderSnapshot, ['action' => 'approved']),
        ];

        History::log(
            type: 'project.bulk-approved',
            actorType: 'admin',
            actorSnapshot: $actor,
            summary: 'Bulk action',
            context: ['channel' => 'backoffice'],
            models: $models,
        );

        $actor['name'] = 'Changed operator';
        $orderSnapshot['name'] = 'Changed order';

        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertDatabaseCount('chief_audit_event_models', 2);
        $event = DB::table('chief_audit_events')->first();
        $this->assertSame('project.article', $event->model_type);
        $this->assertSame('12', $event->model_id);
        $this->assertSame(['channel' => 'backoffice'], json_decode($event->context, true));
        $this->assertSame('Operator', json_decode($event->actor_snapshot, true)['name']);

        $storedModels = DB::table('chief_audit_event_models')->orderBy('id')->get();
        $this->assertSame('Original article', json_decode($storedModels[0]->model_snapshot, true)['name']);
        $this->assertSame(['action' => 'reviewed'], json_decode($storedModels[0]->context, true));
        $this->assertSame('Original order', json_decode($storedModels[1]->model_snapshot, true)['name']);
        $this->assertSame(['action' => 'approved'], json_decode($storedModels[1]->context, true));
    }

    public function test_first_model_is_primary_when_only_one_model_is_supplied(): void
    {
        History::log(
            type: 'project.batch.started',
            actorType: 'system',
            actorSnapshot: ['name' => 'Scheduler'],
            models: [new AuditModelDTO('project.order', '42', ['name' => 'Order at start'])],
        );

        $this->assertDatabaseHas('chief_audit_events', ['type' => 'project.batch.started', 'model_type' => 'project.order', 'model_id' => '42']);
        $this->assertDatabaseHas('chief_audit_event_models', ['model_type' => 'project.order', 'model_id' => '42']);
    }

    public function test_context_rejects_objects_instead_of_serializing_current_model_state(): void
    {
        $this->expectException(InvalidArgumentException::class);

        History::log(
            type: 'project.export',
            actorType: 'system',
            actorSnapshot: ['name' => 'Scheduler'],
            context: ['model' => new stdClass],
        );
    }

    public function test_recursive_context_is_rejected_before_writing(): void
    {
        $context = ['source' => 'project'];
        $context['self'] = &$context;

        $this->expectException(InvalidArgumentException::class);

        History::log(type: 'project.export', actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], context: $context);
    }

    public function test_existing_audit_schema_can_be_upgraded_to_store_context_and_model_links(): void
    {
        $this->artisan('migrate:rollback', ['--step' => 1])->assertExitCode(0);

        $this->assertTrue(Schema::hasTable('chief_audit_events'));
        $this->assertFalse(Schema::hasColumn('chief_audit_events', 'context'));

        $this->artisan('migrate')->assertExitCode(0);

        History::log(
            type: 'project.order.created',
            actorType: 'system',
            actorSnapshot: ['name' => 'Scheduler'],
            context: ['source' => 'project'],
            models: [new AuditModelDTO('project.order', '42', ['name' => 'Order at creation'])],
        );

        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertDatabaseCount('chief_audit_event_models', 1);
    }
}
