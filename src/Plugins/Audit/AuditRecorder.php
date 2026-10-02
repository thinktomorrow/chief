<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Support\Facades\DB;

final class AuditRecorder
{
    public function record(AuditEventDTO $event): AuditEvent
    {
        return DB::transaction(function () use ($event): AuditEvent {
            $record = AuditEvent::query()->create([
                ...$event->toRecord(),
                'recorded_at' => now()->utc(),
            ]);

            foreach ($event->models->all() as $model) {
                AuditEventModel::query()->create([
                    ...$model->toRecord(),
                    'event_id' => $record->getKey(),
                ]);
            }

            return $record;
        });
    }
}
