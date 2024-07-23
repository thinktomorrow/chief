<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

trait HasRowActions
{
    private array $rowActions = [];

    public function rowActions(array $rowActions = []): static
    {
        $this->rowActions = $rowActions;

        return $this;
    }

    public function getRowActions(): array
    {
        return $this->rowActions;
    }
}
