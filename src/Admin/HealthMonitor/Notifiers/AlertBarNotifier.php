<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\HealthMonitor\Notifiers;

use Thinktomorrow\Chief\Admin\HealthMonitor\Checks\HealthCheck;

class AlertBarNotifier implements Notifier
{
    /**
     * @return void
     */
    public function onFailure(HealthCheck $healthCheck)
    {
        session()->flash('alertbarmessage', $healthCheck->message());
    }

    /**
     * @return void
     */
    public function onSuccess(HealthCheck $healthCheck)
    {
    }
}
