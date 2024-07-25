<?php

namespace Thinktomorrow\Chief\TableNew\Filters\Concerns;

/**
 * A value that is active when this element is not selected at all.

 */
trait HasBlankValue
{
    protected $blankValue;

    /**
     * Flag to indicate internally that a value has been explicitly set (via value()).
     * This makes it possible to purposely set null as a value.
     */
    protected bool $blankValueGiven = false;

    public function blankValue(mixed $value): static
    {
        $this->blankValue = $value;
        $this->blankValueGiven = true;

        return $this;
    }

    public function hasBlankValue(): bool
    {
        return $this->blankValueGiven;
    }

    public function getBlankValue(): mixed
    {
        return $this->blankValue;
    }
}
