<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

/** Only fields surviving the viewer's authorization projection are exposed to project code. */
final readonly class VisibleAuditEvent
{
    /** @param list<string> $modelNames */
    public function __construct(
        public string $type,
        public string $category,
        public ?string $summary,
        public string $actorName,
        public ?string $outcome,
        public array $modelNames,
    ) {}

    public static function fromEvent(AuditEvent $event): self
    {
        return new self(
            $event->type,
            $event->category,
            $event->summary,
            $event->actor_snapshot['name'] ?? '',
            $event->outcome,
            $event->models->map(fn (AuditEventModel $model) => $model->model_snapshot['name'] ?? '')->all(),
        );
    }
}
