<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\_partials;

trait WithNullifyEmptyValues
{
    private function nullifyEmptyValues(array $form): array
    {
        foreach ($form as $key => $value) {
            if (is_array($value)) {
                $form[$key] = $this->nullifyEmptyValues($value);
            }

            if ($this->isEmptyValue($value)) {
                $form[$key] = null;
            }
        }

        return $form;
    }

    private function isEmptyValue($value): bool
    {
        // Empty repeat values as well...
        return $value === '' || $value === [[]] || $value === [null];
    }
}
