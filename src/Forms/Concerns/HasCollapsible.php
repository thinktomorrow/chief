<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasCollapsible
{
    protected bool $collapsible = false;

    protected bool $collapsed = false;

    public function collapsible(bool $collapsible = true): static
    {
        $this->collapsible = $collapsible;

        return $this;
    }

    public function isCollapsible(): bool
    {
        return $this->collapsible;
    }

    public function collapsed(bool $collapsed = true): static
    {
        $this->collapsed = $collapsed;

        return $this;
    }

    public function isCollapsed(): bool
    {
        return $this->collapsed;
    }
}
