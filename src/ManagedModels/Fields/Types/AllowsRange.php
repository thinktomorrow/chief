<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

trait AllowsRange
{
    protected int $steps = 1;
    protected int $max = 100;
    protected int $min = 0;

    /**
     * @return RangeField
     */
    public function steps(int $steps = 1): self
    {
        $this->steps = $steps;

        return $this;
    }

    public function getSteps(): int
    {
        return $this->steps;
    }

    /**
     * @return RangeField
     */
    public function max(int $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @return RangeField
     */
    public function min(int $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function getMin(): int
    {
        return $this->min;
    }
}
