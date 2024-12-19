<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

trait WithBulkSelection
{
    public array $bulkSelection = [];

    public function getBulkSelection(): array
    {
        return $this->bulkSelection;
    }

    public function bulkSelectAll(): void
    {
        $this->bulkSelection = $this->getResultsAsCollection()->pluck($this->getModelKeyName())->toArray();
    }

    public function bulkDeselectAll(): void
    {
        $this->bulkSelection = [];
    }

    public function shouldShowSelectAll(): bool
    {
        return $this->resultTotal > $this->resultPageCount && $this->resultTotal > count($this->bulkSelection);
    }
}
