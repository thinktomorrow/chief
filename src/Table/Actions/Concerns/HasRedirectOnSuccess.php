<?php

namespace Thinktomorrow\Chief\Table\Actions\Concerns;

use Closure;

trait HasRedirectOnSuccess
{
    protected ?Closure $redirectOnSuccess = null;

    public function redirectOnSuccess(Closure|string $redirect): static
    {
        $this->redirectOnSuccess = is_callable($redirect) ? $redirect : fn () => $redirect;

        return $this;
    }

    public function hasRedirectOnSuccess(): bool
    {
        return ! is_null($this->redirectOnSuccess);
    }

    public function getRedirectOnSuccess(): Closure
    {
        return $this->redirectOnSuccess;
    }
}
