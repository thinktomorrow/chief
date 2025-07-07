<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

trait HasColumnSelection
{
    protected bool $allowColumnSelection = true;

    /**
     * Defaults to true, meaning the column is selected by default.
     */
    protected bool $isColumnSelected = true;

    public function allowColumnSelection(bool $allowColumnSelection = true): static
    {
        $this->allowColumnSelection = $allowColumnSelection;

        return $this;
    }

    public function disallowColumnSelection(): static
    {
        return $this->allowColumnSelection(false);
    }

    public function isColumnSelectionAllowed(): bool
    {
        return $this->allowColumnSelection;
    }

    public function columnSelectedByDefault(bool $isColumnSelected = true): static
    {
        $this->isColumnSelected = $isColumnSelected;

        return $this;
    }

    public function columnNotSelectedByDefault(): static
    {
        return $this->columnSelectedByDefault(false);
    }

    public function isColumnSelectedByDefault(): bool
    {
        return $this->isColumnSelected;
    }
}
