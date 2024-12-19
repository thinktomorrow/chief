<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

trait HasFilters
{
    private array $filters = [];

    public function filters(array $filters = []): static
    {
        // How to assign: primary, hidden,
        $this->filters = array_merge($this->filters, $filters);

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function removeFilter(string|array $keys): static
    {
        $keys = (array) $keys;

        $this->filters = array_filter($this->filters, fn ($filter) => ! in_array($filter->getKey(), $keys));

        return $this;
    }
}
