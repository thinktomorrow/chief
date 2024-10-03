<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

trait WithMainFilters
{
    public function getMainFilters(): array
    {
        return array_filter($this->getFilters(), fn ($filter) => $filter->isMain());
    }

    private function rejectMainFilters(array $filters): array
    {
        return array_filter($filters, fn ($filter) => ! $filter->isMain());
    }
}
