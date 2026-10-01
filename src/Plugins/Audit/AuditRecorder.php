<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

final class AuditRecorder
{
    public function record(AuditEventDTO $event): AuditEvent
    {
        return AuditEvent::query()->create([
            ...$event->toRecord(),
            'recorded_at' => now()->utc(),
        ]);
    }
}
