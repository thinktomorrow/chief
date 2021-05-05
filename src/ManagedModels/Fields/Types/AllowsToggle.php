<?php

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

trait AllowsToggle
{
    private array $fieldToggles = [];

    /**
     * This allows to toggle visibility of other fields. When this option(s)
     * is selected, the respective fields will be shown or hidden.
     *
     * @param string $fieldKey
     * @param string|array $options
     * @return AllowsToggle
     */
    public function toggleFieldBy(string $fieldKey, $options): self
    {
        $this->fieldToggles[$fieldKey] = (array)$options;

        return $this;
    }

    public function toggledByFields(): bool
    {
        return count($this->fieldToggles) > 0;
    }

    public function getFieldToggles(): array
    {
        return $this->fieldToggles;
    }
}
