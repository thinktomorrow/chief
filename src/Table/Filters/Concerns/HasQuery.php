<?php

namespace Thinktomorrow\Chief\Table\Filters\Concerns;

use Closure;

trait HasQuery
{
    protected ?Closure $query = null;

    public function query(Closure $query): static
    {
        $this->query = $query;

        return $this;
    }

    public function hasQuery(): bool
    {
        return ! is_null($this->query);
    }

    public function getQuery(): Closure
    {
        return $this->query;
    }
}
