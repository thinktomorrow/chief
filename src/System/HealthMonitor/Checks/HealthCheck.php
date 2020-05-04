<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\System\HealthMonitor\Checks;

interface HealthCheck
{
    public function check(): bool;

    public function message(): string;

    /**
     * @return Thinktomorrow\Chief\HealthMonitor\Notifiers\Notifier[]
     */
    public function notifiers(): array;
}
