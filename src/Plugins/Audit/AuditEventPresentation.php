<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

interface AuditEventPresentation
{
    public function present(VisibleAuditEvent $event, AuditType $default): AuditType;
}
