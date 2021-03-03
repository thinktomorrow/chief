<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\HealthMonitor;

use Thinktomorrow\Chief\Admin\HealthMonitor\Checks\HealthCheck;
use Thinktomorrow\Chief\Admin\HealthMonitor\Exceptions\InvalidClassException;

class Monitor
{
    /** @var array */
    private $checks;

    public function __construct()
    {
        $this->checks = config('chief.healthMonitor', []);
    }

    public function check(): void
    {
        foreach ($this->checks as $check) {
            $checkInstance = app($check);

            if (! $checkInstance instanceof HealthCheck) {
                throw new InvalidClassException('Checks must implement Healthcheck interface.');
            }

            $notifiers = $checkInstance->notifiers();

            if (! $checkInstance->check()) {
                foreach ($notifiers as $notifier) {
                    app($notifier)->onFailure($checkInstance);
                }

                break;
            } else {
                foreach ($notifiers as $notifier) {
                    app($notifier)->onSuccess($checkInstance);
                }
            }
        }
    }
}
