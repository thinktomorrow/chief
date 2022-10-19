<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasVisibilityState
{
    protected bool $visible = false;

    public function showContent(): static
    {
        $this->visible = true;

        return $this;
    }

    public function hideContent(): static
    {
        $this->visible = false;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }
}
