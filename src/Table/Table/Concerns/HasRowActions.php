<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use Thinktomorrow\Chief\Table\Actions\Action;
use Thinktomorrow\Chief\Table\Actions\RowAction;

trait HasRowActions
{
    public function rowActions(array $rowActions = []): static
    {
        $this->actions = array_merge($this->actions, array_map(fn (Action $action) => $action->toRowAction(), $rowActions));

        return $this;
    }

    public function getRowActions(): array
    {
        return array_filter($this->actions, fn (Action $action) => $action instanceof RowAction);
    }
}
