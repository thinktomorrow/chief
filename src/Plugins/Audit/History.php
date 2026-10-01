<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

final class History
{
    /**
     * @param  array{name: string, id?: string}  $actorSnapshot
     * @param  array{name: string}|null  $modelSnapshot
     */
    public static function log(
        string $type,
        string $actorType,
        array $actorSnapshot,
        ?string $occurredAt = null,
        string $category = 'general',
        ?string $summary = null,
        ?string $outcome = null,
        ?string $modelType = null,
        ?string $modelId = null,
        ?array $modelSnapshot = null,
    ): ?AuditEvent {
        if (! app()->bound(AuditRecorder::class)) {
            return null;
        }

        return app(AuditRecorder::class)->log(
            type: $type,
            actorType: $actorType,
            actorSnapshot: $actorSnapshot,
            occurredAt: $occurredAt,
            category: $category,
            summary: $summary,
            outcome: $outcome,
            modelType: $modelType,
            modelId: $modelId,
            modelSnapshot: $modelSnapshot,
        );
    }
}
