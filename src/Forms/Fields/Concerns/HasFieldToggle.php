<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasFieldToggle
{
    protected array $fieldToggles = [];

    /**
     * Define conditional fields that should be shown/hidden based on this fields value.
     *
     * @param $fieldName : name of the conditional field
     * @param $values    : values for which the conditional field should be shown
     */
    public function toggleField(string $fieldName, string|array $values): static
    {
        $this->fieldToggles[$fieldName] = (array) $values;

        return $this;
    }

    public function getFieldToggles(): array
    {
        return $this->fieldToggles;
    }
}
