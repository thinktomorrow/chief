<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests\Fixtures;

use Thinktomorrow\Chief\Plugins\Audit\Recording\AuditableEvent;
use Thinktomorrow\Chief\Plugins\Audit\Recording\AuditEventDTO;

final readonly class ProjectOrderApproved implements AuditableEvent
{
    public function __construct(private AuditEventDTO $history) {}

    public function auditEvent(): AuditEventDTO
    {
        return $this->history;
    }
}
