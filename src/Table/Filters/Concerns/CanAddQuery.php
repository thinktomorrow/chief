<?php

namespace Thinktomorrow\Chief\Table\Filters\Concerns;

use Closure;

trait CanAddQuery
{
    /** @var Closure[] */
    protected array $addedQueries = [];

    public function addQuery(Closure $query): static
    {
        $this->addedQueries[] = $query;

        return $this;
    }

    public function getAddedQueries(): array
    {
        return $this->addedQueries;
    }
}
