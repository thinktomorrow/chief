<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\HealthMonitor;

use Thinktomorrow\Chief\HealthMonitor\Checks\HomepageCheck;
use Thinktomorrow\Chief\HealthMonitor\Notifiers\AlertBarNotifier;

class Monitor
{
    private static $checks = [
        HomepageCheck::class => AlertBarNotifier::class
    ];

    public static function check()
    {
        foreach(static::$checks as $check => $notifier)
        {
            $checker = (new $check);
            $notifier = (new $notifier);
            
            if(!$checker->check())
            {
                return $notifier->notify($checker->notify());
            }
        } 
    }
}
