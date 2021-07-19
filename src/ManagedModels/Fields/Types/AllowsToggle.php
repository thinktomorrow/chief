<?php

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

trait AllowsToggle
{
    private array $conditionalFields = [];

    /**
     * Define conditional fields that should be shown/hidden based on this fields value
     * @param fieldName: name of the conditional field
     * @param values: values for which the conditional field should be shown
     */
    public function toggleField($fieldName, $values): self
    {
        $this->conditionalFields[$fieldName] = (array)$values;

        return $this;
    }

    public function getConditionalFieldsData()
    {
        if (! empty($this->conditionalFields)) {
            return json_encode($this->conditionalFields);
        }

        return null;
    }
}
