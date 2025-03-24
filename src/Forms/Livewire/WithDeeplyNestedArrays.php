<?php

namespace Thinktomorrow\Chief\Forms\Livewire;

use Illuminate\Support\Arr;

trait WithDeeplyNestedArrays
{
    protected function flattenArray(array $values): array
    {
        $flatten = Arr::dot($values);

        // Replace dot with _ in the keys to avoid nested array notation
        return array_combine(
            array_map(fn ($key) => str_replace('.', '_', $key), array_keys($flatten)),
            array_values($flatten)
        );
    }

    protected function inflateArray(array $values): array
    {
        $inflated = [];

        foreach ($values as $key => $value) {
            Arr::set($inflated, str_replace('_', '.', $key), $value);
        }

        return $inflated;
    }
}
