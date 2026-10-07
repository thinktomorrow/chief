<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\Listeners;

use Thinktomorrow\Chief\ManagedModels\Events\ChiefActionCompleted;
use Thinktomorrow\Chief\ManagedModels\States\Events\ModelStateUpdated;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

final class DispatchChiefActionCompleted
{
    public function handle(ModelStateUpdated $event): void
    {
        if ($event->transition === 'delete') {
            return;
        }

        event(ChiefActionCompleted::forModels(match ($event->transition) {
            'publish' => 'published',
            'unpublish' => 'unpublished',
            'archive' => 'archived',
            'unarchive' => 'unarchived',
            default => $event->transition,
        }, ModelReference::fromString($event->modelReference)->instance()));
    }
}
