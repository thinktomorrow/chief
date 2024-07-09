<?php

namespace Thinktomorrow\Chief\TableNew\Concerns;

trait HasType
{
    protected string $type = 'grey';
    protected array $typeMap = [];

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getType($value = null): string
    {
        if(! is_string($value) && ! is_int($value)) {
            return $this->type;
        }

        return $this->typeMap[$value] ?? $this->type;
    }

    /**
     * Type mapping by values.
     * Allows you to map a value to a specific type.
     */
    public function typeMap(array $types): static
    {
        $this->typeMap = $types;

        return $this;
    }

    /**
     * Out-of-the-box setup for common Chief resource states.
     */
    public function pageStates(): static
    {
        $this->valueMap([
            'published' => 'online',
            'draft' => 'offline',
            'archived' => 'archived',
        ]);

        return $this->typeMap([
            'online' => 'green',
            'offline' => 'red',
            'archived' => 'red',
        ]);
    }
}
