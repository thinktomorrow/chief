<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Table\Actions\Action;
use Thinktomorrow\Chief\Table\Actions\BulkAction;
use Thinktomorrow\Chief\Table\Actions\RowAction;

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
        return array_filter($this->actions, fn (Action $action) => ! $action instanceof BulkAction && ! $action instanceof RowAction);
    }

    public function removeAction(string|array $keys): static
    {
        $keys = (array) $keys;

        $this->actions = array_filter($this->actions, fn ($action) => ! in_array($action->getKey(), $keys));

        return $this;
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
