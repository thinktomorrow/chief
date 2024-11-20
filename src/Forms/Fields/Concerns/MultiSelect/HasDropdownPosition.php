<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns\MultiSelect;

trait HasDropdownPosition
{
    protected string $dropdownPosition = 'absolute';

    public function dropdownPosition(string $position = 'absolute'): static
    {
        $this->dropdownPosition = $position;

        return $this;
    }

    public function getDropdownPosition(): string
    {
        return $this->dropdownPosition;
    }
}
