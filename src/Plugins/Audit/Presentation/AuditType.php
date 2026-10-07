<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Presentation;

interface AuditType
{
    public function label(): string;

    public function icon(): string;

    public function color(): string;

    public function priority(): string;
}
