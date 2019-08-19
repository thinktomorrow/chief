<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\HealthMonitor\Notifiers;

interface Notifier
{
    public function notify(string $message);
}
