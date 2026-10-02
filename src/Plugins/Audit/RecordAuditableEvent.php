<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

final class RecordAuditableEvent
{
    public function handle(AuditableEvent $event): void
    {
        History::logEvent($event);
    }
}
