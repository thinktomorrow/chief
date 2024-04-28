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
        get filteredOptions() {
            return this.options.filter(option => !this.selection.includes(option.value))
        },
        get selectedOptions() {
            return this.selection.map(value => this.options.find(option => option.value === value));
        },
        addItem: function(){
            this.selection = [...this.selection, ...$el.choices.getValue(true)];
            this.onSelectionChange();
        },
        removeItem: function(value){
            const index = this.selection.indexOf(value);
            this.selection.splice(index, 1);
            this.onSelectionChange();
        },
        sortSelection: function(sortedSelection) {
            this.selection = sortedSelection;
            this.onSelectionChange();
        },
        onSelectionChange: function() {
            // Notify change event for outside listeners, such as the Conditional fields js.
            $dispatch('select-list-change');

            // Notify wired model
            $dispatch('input', this.selection);

            this.updateSelectOptions();
        },
        updateSelectOptions: function() {
            $el.choices.clearStore();
            $el.choices.setChoices(this.filteredOptions);
        },
    }"
    x-init="
        $nextTick(() => {
            updateSelectOptions();
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

    <ol x-sortable x-on:end.stop="sortSelection($event.target.sortable.toArray())">
        <template x-for="(option,index) in selectedOptions" x-bind:key="`${option.value}-${index}`">
            <li x-bind:x-sortable-item="option.value">
                <span x-sortable-handle>PULL</span>
                <span x-html="option.label"></span>
                <span x-on:click="removeItem(option.value)">delete</span>
            </li>
        </template>
    </ol>

    <ul>
        <template x-for="option in selectedOptions" :key="option.value">
            <input type="hidden" name="{{ $name }}[]" x-bind:value="option.value" />
        </template>
    </ul>


    <select
        name="{{ $name }}"
        x-ref="selectEl"
        x-on:change="addItem"
        multiple
    ></select>

</div>
