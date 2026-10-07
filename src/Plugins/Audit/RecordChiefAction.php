<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Thinktomorrow\Chief\ManagedModels\Events\ChiefActionCompleted;

final class RecordChiefAction
{
    public function handle(ChiefActionCompleted $event): void
    {
        History::log(
            type: 'chief.'.(str_contains($event->action, '.') ? $event->action : 'model.'.$event->action),
            actorType: $event->actorType,
            actorSnapshot: $event->actorSnapshot,
            occurredAt: $event->occurredAt,
            category: 'content',
            outcome: 'success',
            summary: 'Model '.$event->action,
            models: array_map(static fn (array $model): AuditModelDTO => new AuditModelDTO(
                $model['type'], $model['id'], ['name' => $model['name']]
            ), $event->models),
        );
    }
}
