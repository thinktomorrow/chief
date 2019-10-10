<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\HealthMonitor\Checks;

interface HealthCheck
{
    public function check(): bool;

    public function message(): string;

    public function notifiers(): array;
}
