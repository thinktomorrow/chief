<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

final class History
{
    public const BINDING = 'chief.audit.history';

    public function __construct(private AuditRecorder $recorder) {}

    public static function logEvent(AuditableEvent $event): AuditEvent
    {
        return self::instance()->recorder->record($event->auditEvent());
    }

    /**
     * @param  array{name: string, id?: string}  $actorSnapshot
     * @param  array<string, mixed>  $context
     * @param  list<AuditModelDTO>  $models  The first model is the primary model.
     */
    public static function log(
        string $type,
        string $actorType,
        array $actorSnapshot,
        ?string $occurredAt = null,
        string $category = 'general',
        ?string $summary = null,
        ?string $outcome = null,
        array $context = [],
        array $models = [],
    ): AuditEvent {
        return self::instance()->recorder->record(new AuditEventDTO(
            type: $type,
            actorType: $actorType,
            actorSnapshot: $actorSnapshot,
            occurredAt: $occurredAt,
            category: $category,
            summary: $summary,
            outcome: $outcome,
            context: $context,
            models: new AuditModelCollectionDTO($models),
        ));
    }

    private static function instance(): self
    {
        return app(self::BINDING);
    }
}
