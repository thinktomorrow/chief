<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

trait HasPagination
{
    private bool $paginate = true;
    private int $paginatePerPage = 20;

    public function paginate(int $paginatePerPage = 20): static
    {
        $this->paginate = true;
        $this->paginatePerPage = $paginatePerPage;

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
