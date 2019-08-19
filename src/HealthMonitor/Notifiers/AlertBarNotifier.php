<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\HealthMonitor\Notifiers;

class AlertBarNotifier implements Notifier
{
    public static function notify(string $message, bool $checked)
    {
        if(! $checked)
        {
            session(['alertbarmessage' => $message]);
        }else{
            session()->forget('alertbarmessage');
        }
    }
}
