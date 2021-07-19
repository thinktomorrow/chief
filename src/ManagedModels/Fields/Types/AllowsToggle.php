<?php

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

trait AllowsToggle
{
    private array $fieldsToTrigger = [];

    public function toggleField($fieldToBeTriggered, $triggerValues): self
    {
        $this->fieldsToTrigger[$fieldToBeTriggered] = (array)$triggerValues;

        return $this;
    }

    public function getFormgroupsToTrigger()
    {
        if (! empty($this->fieldsToTrigger)) {
            return json_encode($this->fieldsToTrigger);
        }

        return null;
    }

    // public function isToggle($currentOption = null): bool
    // {
    //     if ($currentOption === null) {
    //         return count($this->fieldToggles) > 0;
    //     }

    //     foreach (array_keys($this->fieldToggles) as $option) {
    //         if ($option == $currentOption) {
    //             return true;
    //         }
    //     }

    //     return false;
    // }

    // public function getToggleAttributeValue($currentOption): string
    // {
    //     foreach ($this->fieldToggles as $option => $fieldkeys) {
    //         if ($option == $currentOption) {
    //             return implode(',', $fieldkeys);
    //         }
    //     }

    //     return '';
    // }

    // public function getFormgroupsToTrigger()
    // {
    //     return 'test';
    // }

    // public function getValueToTriggerFormgroupsWith()
    // {
    //     return null;
    // }
}
