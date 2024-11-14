<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasStep
{
    protected float|int|null $step = null;

    public function step(float|int|null $step): static
    {
        $this->step = $step;

        return $this;
    }

    public function getStep(): float|int|null
    {
        return $this->step;
    }
}
