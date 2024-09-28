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
}
