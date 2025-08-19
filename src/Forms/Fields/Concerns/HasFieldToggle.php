<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasFieldToggle
{
    protected array $fieldToggles = [];

    /**
     * Define conditional fields that should be shown/hidden based on this fields value.
     *
     * @param  $fieldName  : name of the conditional field
     * @param  $values  : values for which the conditional field should be shown
     */
    public function toggleField(string $fieldName, string|int|bool|array $values): static
    {
        $values = (array) $values;

        // Sanitize each value to a string for js compatibility - also converts boolean to 0 and 1.
        foreach ($values as $key => $value) {
            $values[$key] = (string) $value;
        }

        $this->fieldToggles[$fieldName] = $values;

        return $this;
    }

    public function toggleFields(array $fieldToggles): static
    {
        foreach ($fieldToggles as $fieldName => $values) {
            $this->toggleField($fieldName, $values);
        }

        return $this;
    }

    public function getFieldToggles(): array
    {
        return $this->fieldToggles;
    }
}
