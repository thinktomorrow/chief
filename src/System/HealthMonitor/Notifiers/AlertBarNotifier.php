<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\System\HealthMonitor\Notifiers;

use Thinktomorrow\Chief\System\HealthMonitor\Checks\HealthCheck;

class AlertBarNotifier implements Notifier
{
    public function onFailure(HealthCheck $healthCheck)
    {
        session()->flash('alertbarmessage', $healthCheck->message());
    }

    public function onSuccess(HealthCheck $healthCheck)
    {
    }
}
