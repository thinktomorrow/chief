<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Illuminate\Support\Str;

trait WithPagination
{
    private function getPaginationId(): string
    {
        return 'page'.Str::slug($this->tableReference->getTableKey());
    }

    public function hasPagination(): bool
    {
        return ! $this->isReordering && $this->getTable()->hasPagination();
    }

    private function getPaginationPerPage(): int
    {
        return $this->getSelectedItemsPerPage();
    }

    private function getCurrentPageIndex(): int
    {
        return $this->getPage($this->getPaginationId());
    }

    public function updatedPaginators(int $page, string $pageName): void
    {
        if ($pageName === $this->getPaginationId()) {
            $this->storePaginationState($page);
        }
    }

    protected function getPaginationSessionKey(): string
    {
        return 'table.pagination.'.$this->tableReference->toUniqueString();
    }

    protected function storePaginationState(int $page): void
    {
        session()->put($this->getPaginationSessionKey(), [
            'itemsPerPage' => $this->getSelectedItemsPerPage(),
            'page' => $page,
        ]);
    }
}
