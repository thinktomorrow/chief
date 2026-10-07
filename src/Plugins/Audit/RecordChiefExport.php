<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Thinktomorrow\Chief\Plugins\Export\Events\ChiefExportCompleted;

final class RecordChiefExport
{
    public function handle(ChiefExportCompleted $event): void
    {
        History::log(
            type: 'chief.export.'.$event->kind,
            actorType: 'system',
            actorSnapshot: ['name' => 'Chief export command'],
            occurredAt: $event->occurredAt,
            category: 'export',
            outcome: 'success',
            summary: 'Export completed',
            context: $event->resource === null ? [] : ['resource' => $event->resource],
        );
    }
}
