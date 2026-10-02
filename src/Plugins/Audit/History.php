<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

final class History
{
    public static function logEvent(AuditableEvent $event): ?AuditEvent
    {
        if (! app()->bound(AuditRecorder::class)) {
            return null;
        }

        return app(AuditRecorder::class)->record($event->auditEvent());
    }

    /**
     * @param  array{name: string, id?: string}  $actorSnapshot
     * @param  array{name: string}|null  $modelSnapshot
     * @param  array<string, mixed>  $context
     * @param  array<string, mixed>  $modelContext
     * @param  list<array{modelType: string, modelId: string, modelSnapshot: array{name: string}, context?: array<string, mixed>}>  $relatedModels
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
        array $context = [],
        array $relatedModels = [],
        array $modelContext = [],
    ): ?AuditEvent {
        if (! app()->bound(AuditRecorder::class)) {
            return null;
        }

        return app(AuditRecorder::class)->record(new AuditEventDTO(
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
            context: $context,
            relatedModels: $relatedModels,
            modelContext: $modelContext,
        ));
    }
}
