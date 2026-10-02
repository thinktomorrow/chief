<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

interface AuditableEvent
{
    public function auditEvent(): AuditEventDTO;
}
