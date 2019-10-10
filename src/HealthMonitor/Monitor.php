<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\HealthMonitor;

class Monitor
{
    private static $checks = [
    ];

    public static function check()
    {
        static::$checks = config('thinktomorrow.chief.healthMonitor', []);

        foreach (static::$checks as $check) {
            $checkInstance = app($check);
            $notifiers = $checkInstance->notifiers();

            if (!$checkInstance->check()) {
                foreach($notifiers as $notifier)
                {
                    app($notifier)->onFailure($checkInstance);
                }
                return;
            } else {
                foreach($notifiers as $notifier)
                {
                    app($notifier)->onSuccess($checkInstance);
                }
            }
        }
    }
}
