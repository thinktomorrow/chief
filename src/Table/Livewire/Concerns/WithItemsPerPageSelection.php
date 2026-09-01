<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

trait WithItemsPerPageSelection
{
    public int $selectedItemsPerPage = 20;

    public function mountWithItemsPerPageSelection(): void
    {
        if (! $this->hasPagination()) {
            return;
        }

        $paginationState = session($this->getPaginationSessionKey(), []);
        $this->selectedItemsPerPage = $this->getTable()->getPaginatePerPage();

        if ($this->getTable()->isItemsPerPageSelectionAllowed()) {
            $storedItemsPerPage = $paginationState['itemsPerPage'] ?? null;

            if (in_array($storedItemsPerPage, $this->getItemsPerPageSelection(), true)) {
                $this->selectedItemsPerPage = $storedItemsPerPage;
            }
        }

        $currentPage = $this->getPage($this->getPaginationId());

        if (request()->query->has($this->getPaginationId())) {
            $this->storePaginationState((int) $currentPage);

            return;
        }

        $storedPage = $paginationState['page'] ?? 1;
        $this->setPage(is_int($storedPage) && $storedPage > 0 ? $storedPage : 1, $this->getPaginationId());
    }

    public function updatedSelectedItemsPerPage(): void
    {
        if (! $this->getTable()->isItemsPerPageSelectionAllowed()
            || ! in_array($this->selectedItemsPerPage, $this->getItemsPerPageSelection(), true)) {
            $this->selectedItemsPerPage = $this->getTable()->getPaginatePerPage();
        }

        $this->resetPage($this->getPaginationId());
    }

    public function getItemsPerPageSelection(): array
    {
        return $this->getTable()->getItemsPerPageSelection();
    }

    public function shouldShowItemsPerPageSelection(): bool
    {
        return $this->hasPagination()
            && $this->getTable()->isItemsPerPageSelectionAllowed()
            && $this->resultTotal > min($this->getItemsPerPageSelection());
    }

    private function getSelectedItemsPerPage(): int
    {
        if (! $this->getTable()->isItemsPerPageSelectionAllowed()) {
            return $this->getTable()->getPaginatePerPage();
        }

        if (! in_array($this->selectedItemsPerPage, $this->getItemsPerPageSelection(), true)) {
            return $this->getTable()->getPaginatePerPage();
        }

        return $this->selectedItemsPerPage;
    }
}
