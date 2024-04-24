@props([
  'options' => [],
  'selection' => [],
  'multiple' => false,
  'placeholder' => null,
  'name' => null,
])

// values loop over and create list
// each list item is sortable and deletable
// adding item to list is done by selecting from the multiselect
// After adding, the value is removed from select
// And is not shown in the select dropdown list

<div
        x-cloak
        wire:ignore
        {{ $attributes }}
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
                $el.choices.clearStore();
                $el.choices.setChoices($data.options);

                // Set current value
                $el.choices.setChoiceByValue($data.selection);
            };

            $data.syncSelection = (e) => {

                const newValue = $el.choices.getValue(true);

                // Merge newValue with current selection
                $data.selection = [...$data.selection, ...newValue];

                // Filter out the selected options from the options list
                const filteredOptions = $data.options.filter(option => !$data.selection.includes(option.value));

                // Notify change event for outside listeners, such as the Conditional fields js.
                $dispatch('select-list-change');

                // Reset select list with filtered options
                $el.choices.clearStore();
                $el.choices.setChoices(filteredOptions);
            }

            refreshOptions();
        });
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
              return value1 == value2;
            },
        }
    }">

    <template x-for="(option, index) in selection" :key="index">
        <input type="hidden" name="{{ $name }}[]" x-bind:value="option" />
    </template>

    <ol>
        <template x-for="(option, index) in selection" :key="index">
            <li x-text="option"></li>
        </template>
    </ol>

    <select
            name="{{ $name }}"
            x-ref="selectEl"
            x-on:change="syncSelection"
            multiple
    ></select>

</div>
