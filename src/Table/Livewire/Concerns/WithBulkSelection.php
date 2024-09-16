<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

trait WithBulkSelection
{
    public array $bulkSelection = [];

    public function getBulkSelection(): array
    {
        return $this->bulkSelection;
    }

    public function storeBulkSelection($modelIds): void
    {
        $this->bulkSelection = $modelIds;
    }

    public function bulkSelect($modelId): void
    {
        $this->bulkSelection = array_unique(array_merge($this->bulkSelection, [$modelId]));
    }

    public function bulkDeselect($modelId): void
    {
        $this->bulkSelection = array_diff($this->bulkSelection, [$modelId]);
    }

    public function bulkSelectAll(): void
    {
        // TODO: replace id with getKey()
        $this->bulkSelection = $this->getResults()->pluck('id')->toArray();
    }
}
