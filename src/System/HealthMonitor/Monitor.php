<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\System\HealthMonitor;

use Thinktomorrow\Chief\System\HealthMonitor\Checks\HealthCheck;
use Thinktomorrow\Chief\System\HealthMonitor\Exceptions\InvalidClassException;

class Monitor
{
    /** @var array */
    private $checks;

    public function __construct()
    {
        $this->checks = config('thinktomorrow.chief.healthMonitor', []);
    }

    public function check()
    {
        foreach ($this->checks as $check) {
            $checkInstance = app($check);

            if (!$checkInstance instanceof HealthCheck) {
                throw new InvalidClassException('Checks must implement Healthcheck interface.');
            }

            $notifiers = $checkInstance->notifiers();

            if (!$checkInstance->check()) {
                foreach ($notifiers as $notifier) {
                    app($notifier)->onFailure($checkInstance);
                }
            } else {
                foreach ($notifiers as $notifier) {
                    app($notifier)->onSuccess($checkInstance);
                }
            }
        }
    }
}
