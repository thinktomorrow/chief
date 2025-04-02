<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\_partials;

trait WithNullifyEmptyValues
{
    private function recursiveNullifyEmptyValues(array $form): array
    {
        return collect($form)->map(function ($field) {
            if (is_array($field)) {
                return $this->recursiveNullifyEmptyValues($field);
            }

            if ($field === '') {
                return null;
            }

            return $field;
        })->toArray();
    }
}
