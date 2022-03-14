<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasMinMax
{
    protected ?int $min = null;
    protected ?int $max = null;

    public function min(int $min): static
    {
        $this->min = $min;

        return $this;
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function max(int $max): static
    {
        $this->max = $max;

        return $this;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }
}
