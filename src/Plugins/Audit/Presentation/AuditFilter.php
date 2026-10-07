<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Presentation;

use Thinktomorrow\Chief\Plugins\Audit\Reading\VisibleAuditEvent;

interface AuditFilter
{
    public function label(): string;

    public function matches(VisibleAuditEvent $event, string $value): bool;
}
