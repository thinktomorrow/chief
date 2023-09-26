@props([
  'options' => [],
  'selection' => [],
  'multiple' => false,
])

@once
    {{--    TODO: Tijs extract to our builds--}}
    @push('custom-styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    @endpush
@endonce

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
            noChoicesText: 'geen opties',
            itemSelectText: '',
            uniqueItemText: 'enkel unieke opties zijn mogelijk',
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
        x-on:change="syncSelection()"
        {{ $attributes }}
        {{ $multiple ? 'multiple' : '' }}
    ></select>
</div>
