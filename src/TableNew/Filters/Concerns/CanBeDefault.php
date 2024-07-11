<?php

namespace Thinktomorrow\Chief\TableNew\Filters\Concerns;

trait CanBeDefault {

    private bool $actsAsDefault = false;

    public function actAsDefault(bool $default = true): static
    {
        $this->actsAsDefault = $default;

        return $this;
    }

    public function actsAsDefault(): bool
    {
        return $this->actsAsDefault;
    }
}
