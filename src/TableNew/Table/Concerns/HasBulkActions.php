<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

use Thinktomorrow\Chief\TableNew\Actions\Action;

trait HasBulkActions
{
    /** @var Action[] */
    private array $bulkActions = [];

    public function bulkActions(array $bulkActions = []): static
    {
        $this->bulkActions = array_map(fn (Action $action) => $action->toBulkAction(), $bulkActions);

        return $this;
    }

    public function getBulkActions(): array
    {
        return $this->bulkActions;
    }
}
