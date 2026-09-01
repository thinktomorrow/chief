<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use InvalidArgumentException;

trait HasItemsPerPageSelection
{
    private bool $allowItemsPerPageSelection = true;

    private array $itemsPerPageSelection = [20, 50, 100, 200];

    public function itemsPerPageSelection(array $itemsPerPageSelection): static
    {
        if ($itemsPerPageSelection === []) {
            throw new InvalidArgumentException('The items per page selection cannot be empty.');
        }

        foreach ($itemsPerPageSelection as $itemsPerPage) {
            if (! is_int($itemsPerPage) || $itemsPerPage < 1) {
                throw new InvalidArgumentException('Each items per page option must be a positive integer.');
            }
        }

        $this->itemsPerPageSelection = array_values(array_unique($itemsPerPageSelection));

        return $this;
    }

    public function getItemsPerPageSelection(): array
    {
        $itemsPerPageSelection = array_unique([
            ...$this->itemsPerPageSelection,
            $this->getPaginatePerPage(),
        ]);

        sort($itemsPerPageSelection);

        return array_values($itemsPerPageSelection);
    }

    public function isItemsPerPageSelectionAllowed(): bool
    {
        return $this->allowItemsPerPageSelection;
    }

    public function allowItemsPerPageSelection(bool $allow = true): static
    {
        $this->allowItemsPerPageSelection = $allow;

        return $this;
    }

    public function disallowItemsPerPageSelection(): static
    {
        return $this->allowItemsPerPageSelection(false);
    }
}
