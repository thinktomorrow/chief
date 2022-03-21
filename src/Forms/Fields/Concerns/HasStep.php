<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasStep
{
    protected ?int $step = null;

    public function step(int $step): static
    {
        $this->step = $step;

        return $this;
    }

    public function getStep(): ?int
    {
        return $this->step;
    }
}
