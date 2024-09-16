<?php

namespace Thinktomorrow\Chief\Table\Filters\Concerns;

trait CanHideFromView
{
    private bool $hideFromView = false;

    public function hideFromView(bool $hideFromView = true): static
    {
        $this->hideFromView = $hideFromView;

        return $this;
    }

    public function hiddenFromView(): bool
    {
        return $this->hideFromView;
    }
}
