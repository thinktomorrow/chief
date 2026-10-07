<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Presentation;

use Thinktomorrow\Chief\Plugins\Audit\Reading\VisibleAuditEvent;

interface AuditEventPresentation
{
    public function present(VisibleAuditEvent $event, AuditType $default): AuditType;
}
