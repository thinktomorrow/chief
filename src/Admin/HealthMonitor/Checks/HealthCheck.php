<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\HealthMonitor\Checks;

use Thinktomorrow\Chief\Admin\HealthMonitor\Notifiers\Notifier;

interface HealthCheck
{
    public function check(): bool;

    public function message(): string;

    /**
     * @return Notifier[]
     */
    public function notifiers(): array;
}
