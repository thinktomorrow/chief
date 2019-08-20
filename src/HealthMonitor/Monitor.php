<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\HealthMonitor;

use Thinktomorrow\Chief\HealthMonitor\Checks\HomepageSetCheck;
use Thinktomorrow\Chief\HealthMonitor\Notifiers\AlertBarNotifier;
use Thinktomorrow\Chief\HealthMonitor\Checks\HomepageAccessibleCheck;

class Monitor
{
    private static $checks = [
        HomepageSetCheck::class => AlertBarNotifier::class,
        HomepageAccessibleCheck::class => AlertBarNotifier::class,
    ];

    public static function check()
    {
        foreach(static::$checks as $check => $notifier)
        {
            $checkInstance = app($check);

            if(!$checkInstance->check()) {
                app($notifier)->onFailure($checkInstance);
                return;
            } else {
                app($notifier)->onSuccess($checkInstance);
            }
        }
    }
}
