<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

use Thinktomorrow\Chief\TableNew\Actions\Action;
use Thinktomorrow\Chief\TableNew\Actions\BulkAction;

trait HasBulkActions
{
    public function bulkActions(array $bulkActions = []): static
    {
        $this->actions = array_merge($this->actions, array_map(fn (Action $action) => $action->toBulkAction(), $bulkActions));

        return $this;
    }

    public function getBulkActions(): array
    {
        return array_filter($this->actions, fn (Action $action) => $action instanceof BulkAction);
    }
}
