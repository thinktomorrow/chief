<?php

namespace Thinktomorrow\Chief\Table\Actions\Concerns;

use Closure;

trait HasRedirectTo
{
    protected ?Closure $redirectTo = null;

    public function redirectTo(Closure|string $redirectTo): static
    {
        $this->redirectTo = is_callable($redirectTo) ? $redirectTo : fn () => $redirectTo;

        return $this;
    }

    public function hasRedirectTo(): bool
    {
        return ! is_null($this->redirectTo);
    }

    public function getRedirectTo(): Closure
    {
        return $this->redirectTo;
    }
}
