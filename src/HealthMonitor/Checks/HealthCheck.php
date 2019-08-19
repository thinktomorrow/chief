<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\HealthMonitor\Checks;

interface HealthCheck
{
    public static function check();

    public static function notify();
}
