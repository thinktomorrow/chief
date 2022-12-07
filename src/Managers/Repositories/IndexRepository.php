<?php

namespace Thinktomorrow\Chief\Managers\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;

interface IndexRepository
{
    /**
     * @param callable[] $adjusters
     * @param array $parameterBag
     * @return $this
     */
    public function adjustQuery(iterable $adjusters, array $parameterBag): static;

    /**
     * Results as a tree structure.
     * This is useful for displaying nested results.
     */
    public function getTree(): NestedTree;

    /**
     * Display the results as paginated rows.
     */
    public function getRows(?int $perPage = null): LengthAwarePaginator;
}
