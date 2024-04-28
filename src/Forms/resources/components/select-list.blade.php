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
// TODO: allow rich html for each option in the list --}}

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


    <ol x-sortable x-on:end.stop="sortSelection($event.target.sortable.toArray())" class="border rounded-lg border-grey-200 mt-3">
        <template x-for="(option, index) in selectedOptions" x-bind:key="`${option.value}-${index}`">
            <li x-bind:x-sortable-item="option.value" class="py-2 px-1.5 flex gap-2" :class="{ 'border-t border-grey-200': index > 0 }">
                <button type="button" x-sortable-handle class="shrink-0">
                    <svg class="w-5 h-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256">
                        <path d="M104,60A12,12,0,1,1,92,48,12,12,0,0,1,104,60Zm60,12a12,12,0,1,0-12-12A12,12,0,0,0,164,72ZM92,116a12,12,0,1,0,12,12A12,12,0,0,0,92,116Zm72,0a12,12,0,1,0,12,12A12,12,0,0,0,164,116ZM92,184a12,12,0,1,0,12,12A12,12,0,0,0,92,184Zm72,0a12,12,0,1,0,12,12A12,12,0,0,0,164,184Z"></path>
                    </svg>
                </button>

                <span x-html="option.label" class="body-dark body leading-5 grow"></span>

                <button type="button" x-on:click="removeItem(option.value)" class="shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                    </svg>
                </button>
            </li>
        </template>
    </ol>

    <template x-for="option in selectedOptions" :key="option.value">
        <input type="hidden" name="{{ $name }}[]" x-bind:value="option.value" />
    </template>


    <select
        name="{{ $name }}"
        x-ref="selectEl"
        x-on:change="addItem"
        multiple
    ></select>

</div>
