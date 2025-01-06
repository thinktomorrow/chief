<?php

namespace Thinktomorrow\Chief\Table\Actions\Concerns;

use Closure;

trait HasWhenCondition
{
    protected ?Closure $when = null;

    public function when(Closure|bool $when): static
    {
        $this->when = is_callable($when) ? $when : fn () => $when;

        return $this;
    }

    public function hasWhen(): bool
    {
        return ! is_null($this->when);
    }

    public function getWhen(): Closure
    {
        return $this->when;
    }
}
