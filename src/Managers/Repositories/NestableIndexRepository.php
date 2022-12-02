<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;

class NestableIndexRepository implements IndexRepository
{
    private Builder $builder;

    public function __construct(Builder $builder)
    {
        // Builder
        // Defaults
        // with[]
        $this->builder = $builder;
    }

    public function applyFilters(Filters $filters): void
    {
        // TODO: Implement applyFilters() method.
    }

    public function getTree(): NestedTree
    {
        // TODO: Implement getTree() method.
    }

    public function getRows(): LengthAwarePaginator
    {
        // TODO: Implement getRows() method.
    }
}
