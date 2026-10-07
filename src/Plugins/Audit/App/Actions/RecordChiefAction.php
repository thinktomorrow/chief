<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Actions;

use Thinktomorrow\Chief\ManagedModels\Events\ChiefActionCompleted;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\Recording\AuditModelDTO;

final class RecordChiefAction
{
    public function handle(ChiefActionCompleted $event): void
    {
        History::log(
            type: 'chief.'.(str_contains($event->action, '.') ? $event->action : 'model.'.$event->action),
            actorType: $event->actorType,
            actorSnapshot: $event->actorSnapshot,
            occurredAt: $event->occurredAt,
            category: str_starts_with($event->action, 'user.') ? 'users' : 'content',
            outcome: 'success',
            summary: 'Model '.$event->action,
            models: array_map(static fn (array $model): AuditModelDTO => new AuditModelDTO(
                $model['type'], $model['id'], ['name' => $model['name']]
            ), $event->models),
        );
    }
}
