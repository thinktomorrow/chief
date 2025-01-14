@props([
    'options' => [],
    'selection' => [],
    'multiple' => false,
    'placeholder' => null,
    'name' => null,
    'dropdownPosition' => 'absolute',
])

<div
    x-cloak
    wire:ignore
    {{-- Easily bind data from your Livewire component with wire:model to the "selection" inside this Alpine component --}}
    x-modelable="selection"
    x-data="{
        // Set the selection either if we are in a livewire form based on the given form property value or else on the passed selection
        selection: {{ json_encode((array) $selection) }},
        options: {{ json_encode($options) }},
        syncSelection: () => {},
    }"
    x-init="
        $nextTick(() => {
            const refreshOptions = () => {
                // Reset all options
                $el.choices.clearStore()
                $el.choices.setChoices($data.options)

                // Set current value
                $el.choices.setChoiceByValue($data.selection)
            }

            $data.syncSelection = (e) => {
                const value = $el.choices.getValue(true)
                $data.selection = Array.isArray(value) ? value : [value]

                // Notify change event for outside listeners, such as the Conditional fields js.
                $dispatch('multiselect-change')

                $dispatch('input', $data.selection)
            }

            refreshOptions()
        })
    "
    x-multiselect="{
        selectEl: $refs.selectEl,
        options: {
            allowHTML: true,
            paste: false,
            searchResultLimit: 10,
            placeholder: true,
            placeholderValue: '{{ $placeholder }}',
            shouldSort: false, // Keep sorting as is
            removeItems: true,
            removeItemButton: true,
            duplicateItemsAllowed: false,
            noResultsText: 'Geen resultaten',
            noChoicesText: 'Geen opties',
            itemSelectText: '',
            uniqueItemText: 'Enkel unieke opties zijn mogelijk',
            valueComparer: (value1, value2) => {
                // Default is strict equality, we take it loosely. Otherwise id comparison
                // where it can be either a string or int are not matched up.
                return value1 == value2
            },
        },
    }"
    {{ $attributes->class(['choices-with-static-dropdown' => $dropdownPosition === 'static']) }}
>
    <select
        name="{{ $name }}"
        x-ref="selectEl"
        x-on:change="syncSelection"
        {{ $multiple ? 'multiple' : '' }}
    ></select>
</div>
