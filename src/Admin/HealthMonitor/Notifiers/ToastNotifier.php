<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\HealthMonitor\Notifiers;

use Thinktomorrow\Chief\Admin\HealthMonitor\Checks\HealthCheck;

class ToastNotifier implements Notifier
{
    public function onFailure(HealthCheck $healthCheck)
    {
        session()->now($this->generateUniqueSessionKey(), ['type' => 'warning', 'message' => $healthCheck->message() ]);
    }

    public function onSuccess(HealthCheck $healthCheck)
    {
    }

    private function generateUniqueSessionKey(): string
    {
        $toastId = 'toast_messages.' . mt_rand(0, 9999);

        while (session()->get($toastId)) {
            $toastId = 'toast_messages.' . mt_rand(0, 9999);
        }

        return $toastId;
    }
}
