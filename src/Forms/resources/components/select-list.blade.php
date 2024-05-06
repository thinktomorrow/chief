@props([
    'options' => [],
    'selection' => [],
    'multiple' => false,
    'placeholder' => null,
    'name' => null,
    'grouped' => false,
])

{{-- // values loop over and create list
// each list item is sortable and deletable
// adding item to list is done by selecting from the multiselect
// After adding, the value is removed from select
// And is not shown in the select dropdown list

// TODO: check if wire:model is possible to use here
// TODO: allow rich html for each option in the list --}}

<div
    {{ $attributes }}
    x-cloak
    wire:ignore
    {{-- Easily bind data from your Livewire component with wire:model to the "selection" inside this Alpine component --}}
    x-modelable="selection"
    x-data="{
        // Set the selection either if we are in a livewire form based on the given form property value or else on the passed selection
        selection: {{ json_encode((array) $selection) }},
        options: {{ json_encode($options) }},
        grouped: {{ json_encode($grouped) }},
        showingSelectBox: true,
        get filteredOptions() {
            if (this.grouped) {
                return this.options.map((group) => {
                    group.choices = group.choices.filter((option) => !this.selection.includes(option.value));
                    return group;
                });
            }

            return this.options.filter(option => !this.selection.includes(option.value))
        },
        get selectedOptions() {
            return this.selection.map(value => this.findOptionByValue(value));
        },
        findOptionByValue: function(value) {
            if (this.grouped) {
                for (const group of this.options) {
                    console.log('start', group.choices);
                    for (const option of group.choices) {
                        console.log(option.value);
                    }
                    console.log('end');
                    console.log(group.choices.find(option => option.value === value));
                    return group.choices.find(option => option.value === value);
                }
            }

            return this.options.find(option => option.value.toString() === value.toString());
        },
        addItem: function() {
            this.selection = [...this.selection, ...$el.choices.getValue(true)];
            this.onSelectionChange();
        },
        removeItem: function(value) {
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
        showSelectBox: function() {
            $el.choices.containerOuter.element.classList.remove('hidden');
            $el.choices.input.element.focus();
            this.showingSelectBox = true;
        },
        hideSelectBox: function() {
            if (this.selection.length > 0) {
                $el.choices.containerOuter.element.classList.add('hidden');
                this.showingSelectBox = false;
            }
        },
        hideSelectBoxWhenUnfocused: function() {
            $el.choices.input.element.addEventListener('focusout', () => {
                this.hideSelectBox();
            });
        },
    }"
    x-init="$nextTick(() => {
        updateSelectOptions();
        hideSelectBox();
        hideSelectBoxWhenUnfocused();
    });"
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
    }" class="space-y-2">
    <ol
        x-sortable
        x-on:end.stop="() => {
            sortSelection($event.target.sortable.toArray());
        }"
        class="flex flex-wrap gap-1">
        <template x-for="(option, index) in selectedOptions" x-bind:key="`${option.value}-${index}`">
            <li
                x-sortable-handle
                x-bind:x-sortable-item="option.value"
                class="px-1.5 py-1 flex gap-0.5 bg-grey-100 hover:bg-grey-200 rounded-md cursor-pointer">
                <span x-html="option.label" class="text-sm font-medium leading-5 body body-dark grow"></span>

                <button type="button" x-on:click="removeItem(option.value)" class="shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 my-0.5 text-grey-400 hover:body-dark">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                    </svg>
                </button>
            </li>
        </template>

        <button type="button" x-show="!showingSelectBox" x-on:click="showSelectBox()" class="px-1.5 py-1 flex gap-0.5 bg-grey-100 hover:bg-grey-200 rounded-md cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 my-0.5 body-dark">
                <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
            </svg>
        </button>
    </ol>

    <select name="{{ $name }}" x-ref="selectEl" x-on:change="addItem" multiple></select>

    <template x-for="(option, index) in selectedOptions" x-bind:key="`${option.value}-${index}`">
        <input type="hidden" name="{{ $name }}[]" x-bind:value="option.value" />
    </template>
</div>
