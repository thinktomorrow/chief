<?php

namespace Thinktomorrow\Chief\TableNew\Filters\Concerns;

trait HasValue
{
    protected $value;

    /**
     * Flag to indicate internally that a value has been explicitly set (via value()).
     * This makes it possible to purposely set null as a value.
     */
    protected bool $valueGiven = false;

    public function value(mixed $value): static
    {
        $this->value = $value;
        $this->valueGiven = true;

        return $this;
    }

    public function hasValue(): bool
    {
        return $this->valueGiven;
    }

    public function getValue(): mixed
    {
        return old($this->key, request()->input($this->key, $this->value));
    }
}
