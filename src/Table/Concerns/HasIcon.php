<?php

namespace Thinktomorrow\Chief\Table\Concerns;

trait HasIcon
{
    protected ?string $icon = null;

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }
}
