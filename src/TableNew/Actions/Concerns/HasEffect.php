<?php

namespace Thinktomorrow\Chief\TableNew\Actions\Concerns;

use Closure;

trait HasEffect
{
    protected ?Closure $effect = null;

    public function effect(Closure $effect): static
    {
        $this->effect = $effect;

        return $this;
    }

    public function hasEffect(): bool
    {
        return ! is_null($this->effect);
    }

    public function getEffect(): Closure
    {
        return $this->effect;
    }
}
