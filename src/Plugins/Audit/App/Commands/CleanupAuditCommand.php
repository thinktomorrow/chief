<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class CleanupAuditCommand extends Command
{
    protected $signature = 'chief-audit:cleanup {--dry-run : Show how many events and rich data pieces would expire without changing them}';

    protected $description = 'Remove expired audit events and rich data (schedule this command from your application scheduler)';

    public function handle(): int
    {
        $now = Carbon::now();

        try {
            $eventsDays = $this->days('events_days');
            $richDays = $this->days('rich_data_days');
        } catch (InvalidArgumentException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $eventsBefore = $eventsDays === null ? null : $now->copy()->subDays($eventsDays);
        $richBefore = $richDays === null ? null : $now->copy()->subDays($richDays);

        $events = $eventsBefore === null ? 0 : DB::table('chief_audit_events')->where('occurred_at', '<', $eventsBefore)->count();
        $pieces = $richBefore === null ? 0 : DB::table('chief_audit_rich_data as rich')
            ->join('chief_audit_events as events', 'events.id', '=', 'rich.event_id')
            ->where('events.occurred_at', '<', $richBefore)
            ->where('rich.status', 'available')->count();

        $this->info('Events: '.$events);
        $this->info('Rich data: '.$pieces);

        if ($this->option('dry-run')) {
            return self::SUCCESS;
        }

        if ($richBefore !== null) {
            DB::table('chief_audit_events')->where('occurred_at', '<', $richBefore)
                ->select('id')->chunkById(500, function ($events): void {
                    DB::table('chief_audit_rich_data')->whereIn('event_id', $events->pluck('id'))
                        ->where('status', 'available')->update([
                            'status' => 'removed', 'content' => null, 'metadata' => null,
                            'disk' => null, 'path' => null,
                        ]);
                });
        }

        if ($eventsBefore !== null) {
            DB::table('chief_audit_events')->where('occurred_at', '<', $eventsBefore)
                ->select('id')->chunkById(500, function ($events): void {
                    DB::transaction(function () use ($events): void {
                        DB::table('chief_audit_events')->whereIn('id', $events->pluck('id'))->delete();
                    });
                });
        }

        return self::SUCCESS;
    }

    private function days(string $key): ?int
    {
        $days = config('chief-audit.'.$key);

        if ($days !== null && (! is_int($days) || $days < 0)) {
            throw new InvalidArgumentException('Audit retention '.$key.' must be a non-negative integer or null (unlimited).');
        }

        return $days;
    }
}
