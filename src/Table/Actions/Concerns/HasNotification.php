<?php

namespace Thinktomorrow\Chief\Table\Actions\Concerns;

use Closure;

trait HasNotification
{
    protected ?Closure $notificationOnSuccess = null;
    protected ?Closure $notificationOnFailure = null;

    public function notifyOnSuccess(string|Closure $notification): static
    {
        $this->notificationOnSuccess = is_callable($notification) ? $notification : fn () => $notification;

        return $this;
    }

    public function hasNotificationOnSuccess(): bool
    {
        return ! is_null($this->notificationOnSuccess);
    }

    public function getNotificationOnSuccess(): Closure
    {
        return $this->notificationOnSuccess;
    }

    public function notifyOnFailure(string|Closure $notification): static
    {
        $this->notificationOnFailure = is_callable($notification) ? $notification : fn () => $notification;

        return $this;
    }

    public function hasNotificationOnFailure(): bool
    {
        return ! is_null($this->notificationOnFailure);
    }

    public function getNotificationOnFailure(): Closure
    {
        return $this->notificationOnFailure;
    }
}
