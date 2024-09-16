<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Table\Actions\Action;

trait HasActions
{
    private array $actions = [];

    public function actions(array $actions = []): static
    {
        $this->actions = array_merge($this->actions, $actions);

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

    // TODO: this should work for all type of actions.
    // BUT THEN ASSERT THAT ACTION KEYS ARE UNIQUE ACROSS ALL ACTIONS
    public function findActionDialog(string $actionKey, string $dialogKey): ?Dialog
    {
        $action = $this->findAction($actionKey);

        return $action?->getDialog();
    }
}
