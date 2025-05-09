<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

trait HasFilters
{
    private array $filters = [];

    public function filters(array $filters = []): static
    {
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

    public function orderFilters(array $keysInOrder): static
    {
        $ordered = [];
        $unOrdered = [];

        foreach ($this->filters as $item) {
            if (in_array($item->getKey(), $keysInOrder)) {
                $ordered[(int) array_search($item->getKey(), $keysInOrder)] = $item;
            } else {
                $unOrdered[] = $item;
            }
        }

        // Sort by non-assoc keys so the desired order is maintained
        ksort($ordered);

        $this->filters = array_merge($ordered, $unOrdered);

        return $this;
    }
}
