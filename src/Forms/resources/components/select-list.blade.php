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

// TODO: check if wire:model is possible to use here
// TODO: allow rich html for each option in the list

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
        filteredOptions: {{ json_encode($options) }},
        addItem: () => {},
        onSelectionChange: () => {},
        refreshOptions: () => {},
        removeItem: (value) => {
            const index = $data.selection.indexOf(value);
            $data.selection.splice(index, 1);
            $data.onSelectionChange();
        },
        sortSelection: (sortedSelection) => {
            $data.selection = sortedSelection;
            $data.onSelectionChange();
        }
    }"
    x-init="
        $nextTick(() => {

            const refreshOptions = () => {
                // Filter out the selected options from the options list
                $data.filteredOptions = $data.options.filter(option => !$data.selection.includes(option.value));

                $el.choices.clearStore();
                $el.choices.setChoices($data.filteredOptions);
            }

            $data.onSelectionChange = () => {

                // Notify change event for outside listeners, such as the Conditional fields js.
                $dispatch('select-list-change');

                // Notify wired model
                $dispatch('input', $data.selection);

                refreshOptions();
            }

            $data.addItem = (e) => {

                // Merge newValue with current selection
                $data.selection = [...$data.selection, ...$el.choices.getValue(true)];
                $data.onSelectionChange();
            }

            refreshOptions();
        });
    "
    x-multiselect="{
        selectEl: $refs.selectEl,
        options: {
            allowHTML: true,
            paste: false,
            searchResultLimit: 30,
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

    <template x-for="(option, index) in selection" :key="option">
        <input type="hidden" name="{{ $name }}[]" x-bind:value="option" />
    </template>

    <ol x-sortable x-on:end.stop="sortSelection($event.target.sortable.toArray())">
        <template x-for="(option, index) in selection" :key="option">
            <li :x-sortable-item="option">
                <span x-sortable-handle>PULL</span>
                <span x-text="index"></span>
                <span x-text="option"></span>
                <span x-on:click="removeItem(option)">delete</span>
            </li>
        </template>
    </ol>

    <select
        name="{{ $name }}"
        x-ref="selectEl"
        x-on:change="addItem"
        multiple
    ></select>

</div>
