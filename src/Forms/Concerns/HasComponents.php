<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasComponents
{
    protected array $components = [];

    public function components(array $components): static
    {
        $this->components = $components;

        return $this;
    }

    public function addComponent($component): void
    {
        $this->components[] = $component;
    }

    /**
     * Short convenience alternative to components().
     * Helps with a bit cleaner form definition on the resource.
     */
    public function items(array $components): static
    {
        return $this->components($components);
    }

    public function getComponents(): array
    {
        return $this->components;
    }
}
