<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Actions;

use Thinktomorrow\Chief\Admin\Authentication\Events\ChiefLoginCompleted;
use Thinktomorrow\Chief\Plugins\Audit\History;

final class RecordChiefLogin
{
    public function handle(ChiefLoginCompleted $event): void
    {
        History::log(
            type: 'chief.login.'.$event->outcome,
            actorType: $event->actorType,
            actorSnapshot: $event->actorSnapshot,
            occurredAt: $event->occurredAt,
            category: 'authentication',
            outcome: $event->outcome,
            summary: $event->outcome === 'success' ? 'Admin logged in' : 'Admin login failed',
            context: $event->context,
        );
    }
}
