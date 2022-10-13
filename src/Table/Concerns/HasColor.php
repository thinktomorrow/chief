<?php

namespace Thinktomorrow\Chief\Table\Concerns;

trait HasColor
{
    protected ?string $color = null;

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }
}
