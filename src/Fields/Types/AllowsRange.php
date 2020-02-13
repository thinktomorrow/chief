<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

trait AllowsRange
{
    protected $steps = 1;
    protected $max = 100;
    protected $min = 0;

    public function steps(int $steps = 1)
    {
        $this->steps = $steps;

        return $this;
    }

    public function getSteps(): int
    {
        return $this->steps;
    }

    public function max(int $max)
    {
        $this->max = $max;

        return $this;
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function min(int $min)
    {
        $this->min = $min;

        return $this;
    }

    public function getMin(): int
    {
        return $this->min;
    }
}
