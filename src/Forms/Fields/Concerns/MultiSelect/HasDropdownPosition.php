<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns\MultiSelect;

trait HasDropdownPosition
{
    protected ?string $dropdownPosition = null;

    private static $defaultPosition = 'absolute';

    public function dropdownPosition(string $position = 'absolute'): static
    {
        $this->dropdownPosition = $position;

        return $this;
    }

    public function dropdownPositionAbsolute(): static
    {
        return $this->dropdownPosition('absolute');
    }

    public function dropdownPositionStatic(): static
    {
        return $this->dropdownPosition('static');
    }

    public function getDropdownPosition(): string
    {
        return $this->dropdownPosition ?? static::$defaultPosition;
    }

    public function hasDropdownPosition(): bool
    {
        return ! is_null($this->dropdownPosition);
    }
}
