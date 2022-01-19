<?php

namespace Thinktomorrow\Chief\Forms\Fields\Types;

trait AllowsConditionalFields
{
    private array $conditionalFields = [];

    /**
     * Define conditional fields that should be shown/hidden based on this fields value
     *
     * @param $fieldName : name of the conditional field
     * @param $values    : values for which the conditional field should be shown
     * @return AllowsConditionalFields
     */
    public function toggleField($fieldName, $values): self
    {
        $this->conditionalFields[$fieldName] = (array)$values;

        return $this;
    }

    public function getConditionalFieldsData()
    {
        if (! empty($this->conditionalFields)) {
            return $this->conditionalFields;
        }

        return null;
    }
}
