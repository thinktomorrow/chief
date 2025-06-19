<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

trait HasLivewireListeners
{
    private array $listeners = [];

    public function listeners(array $listeners): static
    {
        $this->listeners = $listeners;

        return $this;
    }

    public function getListeners(): array
    {
        return $this->listeners;
    }
}
