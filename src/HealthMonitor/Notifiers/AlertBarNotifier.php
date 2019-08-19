<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\HealthMonitor\Notifiers;

class AlertBarNotifier implements Notifier
{
    public function notify(string $message)
    {
        session(['alertbarmessage' => $message]);
    }
}
