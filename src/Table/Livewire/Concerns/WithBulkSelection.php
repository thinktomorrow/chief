<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

trait WithBulkSelection
{
    public array $bulkSelection = [];

    public function getBulkSelection(): array
    {
        return $this->bulkSelection;
    }

//    public function bulkSelect(array $modelIds): void
//    {
//        $this->bulkSelection = array_values(array_unique(array_merge($this->bulkSelection, $modelIds)));
//    }
//
//    public function bulkDeselect(array $modelIds): void
//    {
//        $this->bulkSelection = array_values(array_diff($this->bulkSelection, $modelIds));
//    }

    public function bulkSelectAll(): void
    {
        $this->bulkSelection = $this->getResultsAsCollection()->pluck($this->getModelKeyName())->toArray();
    }

    public function bulkDeselectAll(): void
    {
        $this->bulkSelection = [];
    }
}
