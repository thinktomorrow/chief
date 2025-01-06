<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use Thinktomorrow\Chief\Table\Actions\Action;
use Thinktomorrow\Chief\Table\Actions\BulkAction;

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
