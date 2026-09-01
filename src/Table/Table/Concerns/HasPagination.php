<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

trait HasPagination
{
    private bool $paginate = true;

    private int $paginatePerPage = 20;

    public function paginate(int $paginatePerPage = 20, array $itemsPerPageSelection = [20, 50, 100, 200]): static
    {
        if ($paginatePerPage < 1) {
            throw new \InvalidArgumentException('The items per page must be a positive integer.');
        }

        $this->paginate = true;
        $this->paginatePerPage = $paginatePerPage;
        $this->itemsPerPageSelection($itemsPerPageSelection);

        return $this;
    }

    public function hasPagination(): bool
    {
        return $this->paginate;
    }

    public function getPaginatePerPage(): int
    {
        return $this->paginatePerPage;
    }

    public function noPagination(): static
    {
        $this->paginate = false;

        return $this;
    }
}
