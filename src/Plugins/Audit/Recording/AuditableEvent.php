<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Recording;

interface AuditableEvent
{
    public function auditEvent(): AuditEventDTO;
}
