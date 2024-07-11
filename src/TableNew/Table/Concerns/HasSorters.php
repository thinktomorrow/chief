<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

use Thinktomorrow\Chief\TableNew\Sorters\TreeSort;

trait HasSorters
{
    private array $sorters = [];
    private bool $withDefaultTreeSorting = true;

    public function sorters(array $sorters = []): static
    {
        // How to assign: primary, hidden,
        $this->sorters = array_merge($this->sorters, $sorters);

        return $this;
    }

    public function getSorters(): array
    {
        return $this->sorters;
    }

    public function withoutDefaultTreeSorting(): static
    {
        $this->withDefaultTreeSorting = false;

        $this->sorters = array_filter($this->sorters, fn ($sorter) => ! $sorter instanceof TreeSort);

        return $this;
    }

    public function withDefaultTreeSorting(): static
    {
        $this->withDefaultTreeSorting = true;

        return $this;
    }

    private function addDefaultTreeSorting(): static
    {
        if ($this->withDefaultTreeSorting) {
            $this->sorters[] = TreeSort::default();
        }

        return $this;
    }
}
