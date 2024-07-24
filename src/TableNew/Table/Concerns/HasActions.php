<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

use Thinktomorrow\Chief\TableNew\Actions\Action;

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

    public function findAction(string $key): ?Action
    {
        return collect($this->actions)->first(fn (Action $action) => $action->getKey() === $key);
    }
}
