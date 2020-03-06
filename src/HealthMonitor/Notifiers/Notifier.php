<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\HealthMonitor\Notifiers;

use Thinktomorrow\Chief\HealthMonitor\Checks\HealthCheck;

interface Notifier
{
    public function onFailure(HealthCheck $healthCheck);

    public function onSuccess(HealthCheck $healthCheck);
}
