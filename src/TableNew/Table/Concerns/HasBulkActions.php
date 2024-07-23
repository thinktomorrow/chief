<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

trait HasBulkActions
{
    private array $bulkActions = [];

    public function bulkActions(array $bulkActions = []): static
    {
        $this->bulkActions = $bulkActions;

        return $this;
    }

    public function getBulkActions(): array
    {
        return $this->bulkActions;
    }
}
