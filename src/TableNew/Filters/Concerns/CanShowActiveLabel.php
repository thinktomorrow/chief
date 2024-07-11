<?php

namespace Thinktomorrow\Chief\TableNew\Filters\Concerns;

trait CanShowActiveLabel
{
    private bool $showActiveLabel = true;

    public function showActiveLabel(bool $showsActiveLabel = true): static
    {
        $this->showActiveLabel = $showsActiveLabel;

        return $this;
    }

    public function hideActiveLabel(): static
    {
        $this->showActiveLabel = false;

        return $this;
    }

    public function showsActiveLabel(): bool
    {
        return $this->showActiveLabel;
    }
}
