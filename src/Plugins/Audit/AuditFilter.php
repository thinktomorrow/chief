<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

interface AuditFilter
{
    public function label(): string;

    public function matches(VisibleAuditEvent $event, string $value): bool;
}
