<?php

namespace Thinktomorrow\Chief\TableNew\Actions\Concerns;

trait HasModal
{
    protected bool $withModal = false;

    public function modal(bool $withModal = true): static
    {
        $this->withModal = $withModal;

        return $this;
    }

    public function hasModal(): bool
    {
        return $this->withModal;
    }
}
