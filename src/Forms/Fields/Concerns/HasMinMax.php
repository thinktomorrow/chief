<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasMinMax
{
    protected ?string $min = null;
    protected ?string $max = null;

    public function min(string|int $min): static
    {
        $this->min = (string) $min;

        return $this;
    }

    public function getMin(): ?string
    {
        return $this->min;
    }

    public function max(string|int $max): static
    {
        $this->max = (string) $max;

        return $this;
    }

    public function getMax(): ?string
    {
        return $this->max;
    }
}
