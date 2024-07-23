<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

trait HasActions
{
    private array $actions = [];

    public function actions(array $actions = []): static
    {
        // How to assign: primary, hidden,
        $this->actions = $actions;

        return $this;
    }

    public function getActions(): array
    {
        return $this->actions;
    }
}
