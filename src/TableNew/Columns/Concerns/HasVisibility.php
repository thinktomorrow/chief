<?php

namespace Thinktomorrow\Chief\TableNew\Columns\Concerns;

trait HasVisibility
{
    protected bool $visible = true;

    public function visible(bool $visible = true): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function hidden(): static
    {
        return $this->visible(false);
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function isHidden(): bool
    {
        return !$this->isVisible();
    }
}
