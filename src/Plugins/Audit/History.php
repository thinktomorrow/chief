<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

final class History
{
    /**
     * @param  array{type: string, category: string, summary: string, actor_type: string, actor_snapshot: array{name: string, id?: string}, outcome?: string, occurred_at?: string, model_type?: string, model_id?: string, model_snapshot?: array{name: string}}  $event
     */
    public static function log(array $event): ?AuditEvent
    {
        if (! app()->bound(AuditRecorder::class)) {
            return null;
        }

        return app(AuditRecorder::class)->log($event);
    }
}
