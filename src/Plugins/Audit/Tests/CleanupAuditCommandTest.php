<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Plugins\Audit\AuditModelDTO;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\RichData;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class CleanupAuditCommandTest extends ChiefTestCase
{
    protected function getPackageProviders($app)
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_default_day_boundaries_use_occurrence_not_recording_time_and_dry_run_reports_counts(): void
    {
        Carbon::setTestNow('2026-10-07 12:00:00');

        $old = $this->event('2024-10-07 11:59:59');
        $boundary = $this->event('2024-10-07 12:00:00');
        $fresh = $this->event('2026-06-29 12:00:00');
        $richBoundary = $this->event('2026-06-29 11:59:59');
        $this->assertSame('2026-10-07 12:00:00', DB::table('chief_audit_events')->where('id', $old)->value('recorded_at'));

        $this->assertSame(0, Artisan::call('chief-audit:cleanup', ['--dry-run' => true]));
        $output = Artisan::output();
        $this->assertStringContainsString('Events: 1', $output);
        $this->assertStringContainsString('Rich data: 3', $output);
        $this->assertDatabaseCount('chief_audit_events', 4);
        $this->assertDatabaseCount('chief_audit_rich_data', 4);

        $this->assertSame(0, Artisan::call('chief-audit:cleanup'));
        $this->assertDatabaseMissing('chief_audit_events', ['id' => $old]);
        $this->assertDatabaseHas('chief_audit_events', ['id' => $boundary]);
        $this->assertDatabaseHas('chief_audit_rich_data', ['event_id' => $fresh, 'status' => 'available']);
        $this->assertDatabaseHas('chief_audit_rich_data', ['event_id' => $richBoundary, 'status' => 'removed', 'content' => null, 'metadata' => null]);
        $this->assertDatabaseHas('chief_audit_event_models', ['event_id' => $richBoundary, 'has_rich_data' => true]);
    }

    public function test_terms_are_independent_unlimited_and_read_again_on_each_run(): void
    {
        Carbon::setTestNow('2026-10-07 12:00:00');
        $event = $this->event('2026-10-06 12:00:00');

        config()->set('chief-audit.events_days', null);
        config()->set('chief-audit.rich_data_days', 1);
        Artisan::call('chief-audit:cleanup');
        $this->assertDatabaseHas('chief_audit_events', ['id' => $event]);
        $this->assertDatabaseHas('chief_audit_rich_data', ['event_id' => $event, 'status' => 'available']);

        config()->set('chief-audit.rich_data_days', 0);
        Artisan::call('chief-audit:cleanup');
        $this->assertDatabaseHas('chief_audit_rich_data', ['event_id' => $event, 'status' => 'removed']);

        $next = $this->event('2026-10-06 12:00:00');
        config()->set('chief-audit.rich_data_days', null);
        config()->set('chief-audit.events_days', 1);
        Artisan::call('chief-audit:cleanup');
        $this->assertDatabaseHas('chief_audit_events', ['id' => $next]);
        $this->assertDatabaseHas('chief_audit_rich_data', ['event_id' => $next, 'status' => 'available']);

        config()->set('chief-audit.events_days', 0);
        Artisan::call('chief-audit:cleanup');
        $this->assertDatabaseCount('chief_audit_events', 0);
        $this->assertDatabaseCount('chief_audit_rich_data', 0);
    }

    public function test_expiring_an_event_removes_its_links_changes_and_rich_data(): void
    {
        Carbon::setTestNow('2026-10-07 12:00:00');
        $event = $this->event('2020-01-01 00:00:00');
        DB::table('chief_audit_event_models')->where('event_id', $event)->update(['changes' => '{"title":{"before":"A","after":"B"}}']);
        $this->assertNotNull(DB::table('chief_audit_event_models')->where('event_id', $event)->value('changes'));

        Artisan::call('chief-audit:cleanup');

        $this->assertDatabaseMissing('chief_audit_events', ['id' => $event]);
        $this->assertDatabaseMissing('chief_audit_event_models', ['event_id' => $event]);
        $this->assertDatabaseMissing('chief_audit_rich_data', ['event_id' => $event]);
    }

    public function test_dry_run_counts_all_pieces_cascaded_by_expired_events_without_double_counting(): void
    {
        Carbon::setTestNow('2026-10-07 12:00:00');
        $old = $this->event('2020-01-01 00:00:00');
        $fresh = $this->event('2026-10-07 00:00:00');
        DB::table('chief_audit_rich_data')->where('event_id', $old)->update(['status' => 'removed']);
        config()->set('chief-audit.rich_data_days', null);

        Artisan::call('chief-audit:cleanup', ['--dry-run' => true]);
        $output = Artisan::output();
        $this->assertStringContainsString('Events: 1', $output);
        $this->assertStringContainsString('Rich data: 1', $output);
        $this->assertDatabaseHas('chief_audit_rich_data', ['event_id' => $old, 'status' => 'removed']);
        $this->assertDatabaseHas('chief_audit_rich_data', ['event_id' => $fresh, 'status' => 'available']);

        config()->set('chief-audit.rich_data_days', 0);
        Artisan::call('chief-audit:cleanup', ['--dry-run' => true]);
        $this->assertStringContainsString('Rich data: 2', Artisan::output());
        $this->assertDatabaseCount('chief_audit_rich_data', 2);

        config()->set('chief-audit.events_days', null);
        config()->set('chief-audit.rich_data_days', null);
        Artisan::call('chief-audit:cleanup', ['--dry-run' => true]);
        $output = Artisan::output();
        $this->assertStringContainsString('Events: 0', $output);
        $this->assertStringContainsString('Rich data: 0', $output);
    }

    private function event(string $occurredAt): int
    {
        return (int) History::log(
            type: 'project.test', actorType: 'system', actorSnapshot: ['name' => 'System'],
            occurredAt: $occurredAt, models: [new AuditModelDTO('article', uniqid(), ['name' => 'Article'])],
            richData: [RichData::text('Sensitive history', 0)],
        )->getKey();
    }
}
