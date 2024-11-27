<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use InvalidArgumentException;
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

    public function findActionDialog(string $actionKey, string $dialogKey, array $parameters = []): ?Dialog
    {
        $action = $this->findAction($actionKey);

        if (! $action) {
            return null;
        }

        // TODO: here we should use the dialogKey to get the correct dialog in case of multiple...
        $dialog = call_user_func_array($action->getDialogResolver(), $parameters);

        if (! $dialog instanceof Dialog) {
            throw new InvalidArgumentException('Dialog resolver should return a Dialog instance.');
        }

        return $dialog;
    }
}
