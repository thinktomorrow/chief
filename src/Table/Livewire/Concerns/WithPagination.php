<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Illuminate\Support\Str;

trait WithPagination
{
    private function getPaginationId(): string
    {
        return 'page' . Str::slug($this->tableReference->getTableKey());
    }

    public function hasPagination(): bool
    {
        return $this->getTable()->hasPagination();
    }

    private function getPaginationPerPage(): int
    {
        return $this->getTable()->getPaginatePerPage();
    }

    private function getCurrentPageIndex(): int
    {
        return $this->getPage($this->getPaginationId());
    }
}
