<?php

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

trait AllowsToggle
{
    private array $fieldToggles = [];

    /**
     * This allows to toggle visibility of other fields. When this option(s)
     * is selected, the respective fields will be shown or hidden.
     *
     * @param string $option
     * @param array|string $fieldKeys
     * @return AllowsToggle
     */
    public function toggleField(string $option, $fieldKeys): self
    {
        $this->fieldToggles[$option] = (array)$fieldKeys;

        return $this;
    }

    public function isToggle($currentOption = null): bool
    {
        if($currentOption === null) return count($this->fieldToggles) > 0;

        foreach($this->fieldToggles as $option => $fieldkeys) {
            if($option == $currentOption) {
                return true;
            }
        }

        return false;
    }

    public function getToggleAttributeValue($currentOption): string
    {
        foreach($this->fieldToggles as $option => $fieldkeys) {

            if($option == $currentOption) return implode(',',$fieldkeys);
        }

        return '';
    }
}
