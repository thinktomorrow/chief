<?php

namespace Thinktomorrow\Chief\Table\Concerns;

trait HasIcon
{
    protected ?string $prependIcon = null;
    protected ?string $appendIcon = null;

    public function getPrependIcon(): ?string
    {
        return $this->prependIcon;
    }

    public function prependIcon(string $icon): static
    {
        $this->prependIcon = $icon;

        return $this;
    }

    public function getAppendIcon(): ?string
    {
        return $this->appendIcon;
    }

    public function appendIcon(string $icon): static
    {
        $this->appendIcon = $icon;

        return $this;
    }
}
