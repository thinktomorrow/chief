<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use RuntimeException;
use Thinktomorrow\Chief\Plugins\Audit\AuditEventDTO;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\RecordAuditableEvent;
use Thinktomorrow\Chief\Plugins\Audit\Tests\Fixtures\ProjectOrderApproved;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class AuditableEventTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_only_an_explicit_listener_records_a_dispatched_event_once(): void
    {
        $event = new ProjectOrderApproved(new AuditEventDTO(
            type: 'project.order.approved',
            actorType: 'external',
            actorSnapshot: ['name' => 'Partner at approval', 'id' => 'partner-4'],
            modelType: 'project.order',
            modelId: '42',
            modelSnapshot: ['name' => 'Order at approval'],
        ));

        Event::dispatch($event);
        $this->assertDatabaseCount('chief_audit_events', 0);

        Event::listen(ProjectOrderApproved::class, RecordAuditableEvent::class);
        Event::dispatch($event);

        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertDatabaseCount('chief_audit_event_models', 1);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'project.order.approved', 'actor_type' => 'external']);
    }

    public function test_direct_event_registration_shares_the_same_synchronous_write_path(): void
    {
        $event = new ProjectOrderApproved(new AuditEventDTO(
            type: 'project.order.approved',
            actorType: 'system',
            actorSnapshot: ['name' => 'Scheduler'],
        ));

        History::logEvent($event);

        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'project.order.approved', 'actor_type' => 'system']);
    }

    public function test_rolled_back_success_disappears_but_an_explicit_failure_can_be_recorded_separately(): void
    {
        Event::listen(ProjectOrderApproved::class, RecordAuditableEvent::class);

        try {
            DB::transaction(function (): void {
                Event::dispatch(new ProjectOrderApproved(new AuditEventDTO(
                    type: 'project.order.approved',
                    actorType: 'system',
                    actorSnapshot: ['name' => 'Scheduler'],
                    modelType: 'project.order',
                    modelId: '42',
                    modelSnapshot: ['name' => 'Order at approval'],
                )));

                throw new RuntimeException('Transaction failed.');
            });
        } catch (RuntimeException $exception) {
            $this->assertSame('Transaction failed.', $exception->getMessage());
        }

        $this->assertDatabaseCount('chief_audit_events', 0);
        $this->assertDatabaseCount('chief_audit_event_models', 0);

        History::log(type: 'project.order.rejected', actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], outcome: 'failure');

        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'project.order.rejected', 'outcome' => 'failure']);
    }
}
