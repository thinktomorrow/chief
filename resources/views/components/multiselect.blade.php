@props([
  'options' => [],
  'selection' => [],
  'multiple' => false,
])

<div
    x-cloak
    wire:ignore
    x-data="{
        selection: {{ json_encode((array) $selection) }},
        options: {{ json_encode($options) }},
        syncSelection: () => {}
    }"
    x-multiselect="{
        selectEl: $refs.selectEl,
        options: {
            allowHTML: true,
            paste: false,
            searchResultLimit: 10,
            shouldSort: false, // Keep sorting as is
            removeItems: true,
            removeItemButton: true,
            duplicateItemsAllowed: false,
            noResultsText: 'Geen resultaten',
            noChoicesText: 'Geen opties',
            itemSelectText: '',
            uniqueItemText: 'Enkel unieke opties zijn mogelijk',
            valueComparer: (value1, value2) => {
              return value1 == value2; // Default is strict equality, we take it loosely.
            },
        }
    }"
    x-init="
        $nextTick(() => {
            const refreshOptions = () => {
                // Reset all options
                $el.choices.clearStore();
                $el.choices.setChoices($data.options);

                // Set current value
                $el.choices.setChoiceByValue($data.selection);
            };

            $data.syncSelection = (e) => {
                const value = $el.choices.getValue(true);
                $data.selection = Array.isArray(value) ? value : [value];
            }

            refreshOptions();
        });
    "
>
    <select
        x-ref="selectEl"
        x-on:change="() => {
            $dispatch('multiselect-change');

            syncSelection();
        }"
        {{ $attributes }}
        {{ $multiple ? 'multiple' : '' }}
    ></select>
</div>
