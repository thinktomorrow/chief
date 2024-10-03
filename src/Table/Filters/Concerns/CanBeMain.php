<?php

namespace Thinktomorrow\Chief\Table\Filters\Concerns;

trait CanBeMain
{
    protected bool $isMain = false;

    public function main(bool $isMain = true): static
    {
        $this->isMain = $isMain;

        return $this;
    }

    public function isMain(): bool
    {
        return $this->isMain;
    }
}
