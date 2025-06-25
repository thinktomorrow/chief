@props([
    'options' => [],
    'selection' => [],
    'multiple' => false,
    'placeholder' => null,
    'name' => null,
    'grouped' => false,
])

{{--
    // values loop over and create list
    // each list item is sortable and deletable
    // adding item to list is done by selecting from the multiselect
    // After adding, the value is removed from select
    // And is not shown in the select dropdown list

    // Test: saving + livewire saving (e.g. in file field)

    // Testing:
    // - the already selected values are shown in the list
    // - the selected values are updated after adding an item
    // - the selected values are updated after removing an item
    // - hidden input fields are populated with the selected values
    // - the select dropdown is updated after adding an item
    // - the select dropdown is updated after removing an item
    // - the select dropdown is hidden after adding an item
    // - the selected values are updated after sorting

    // https://dev.to/thormeier/simple-and-effective-unit-testing-alpine-js-components-with-jest-13ig

    // TODO: check if wire:model is possible to use here
    // TODO: allow rich html for each option in the list
--}}

<div
    {{ $attributes }}
    x-cloak
    wire:ignore
    x-data="selectlist({
        options: {{ Js::from($options) }},
        selection: {{ Js::from($selection) }},
        multiple: {{ Js::from($multiple) }},
        grouped: {{ Js::from($grouped) }},
    })"
    {{-- Easily bind data from your Livewire component with wire:model to the "selection" inside this Alpine component --}}
    x-modelable="selection"
    x-multiselect="{
        selectEl: $refs.selectEl,
        options: {
            allowHTML: true,
            paste: false,
            searchResultLimit: 20,
            resetScrollPosition: false, // Keep scroll in position when selecting
            placeholder: true,
            placeholderValue: '{{ $placeholder }}',
            shouldSort: false, // Keep sorting as is
            removeItems: true,
            removeItemButton: true,
            duplicateItemsAllowed: false,
            noResultsText: 'Geen resultaten',
            noChoicesText: 'Geen opties',
            itemSelectText: 'klik op enter om te selecteren',
            uniqueItemText: 'Enkel unieke opties zijn mogelijk',
            valueComparer: (value1, value2) => {
                // Default is strict equality, we take it loosely. Otherwise id comparison
                // where it can be either a string or int are not matched up.
                return value1 == value2
            },
        },
    }"
    class="space-y-2"
>
    <ol
        x-sortable
        x-sortable-ghost-class="select-list-ghost"
        x-sortable-drag-class="select-list-drag"
        x-on:end.stop="
            () => {
                $data.sortSelection($event.target.sortable.toArray())
            }
        "
        class="flex flex-wrap gap-1"
    >
        <template x-for="(option, index) in $data.selectedOptions" x-bind:key="`${option.value}-${index}`">
            <li
                x-sortable-handle
                x-bind:x-sortable-item="option.value"
                @class([
                    'flex cursor-pointer gap-0.5 rounded-md bg-grey-100 px-1.5 py-1 hover:bg-grey-200',
                    '[&.select-list-ghost]:opacity-25',
                    '[&.select-list-drag]:opacity-90',
                ])
            >
                <span x-html="option.label" class="body body-dark grow text-sm font-medium leading-5"></span>

                <button type="button" x-on:click="$data.removeItem(option.value)" class="shrink-0">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        class="hover:body-dark my-0.5 h-4 w-4 text-grey-400"
                    >
                        <path
                            d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"
                        />
                    </svg>
                </button>
            </li>
        </template>

        <button
            type="button"
            x-show="$data.allowSelectBox && !$data.showingSelectBox"
            x-on:click="$data.showSelectBox()"
            class="flex cursor-pointer gap-0.5 rounded-md bg-grey-100 px-1.5 py-1 hover:bg-grey-200"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                class="body-dark my-0.5 h-4 w-4"
            >
                <path
                    d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"
                />
            </svg>
        </button>
    </ol>

    <div x-show="$data.showingSelectBox">
        <select name="{{ $name }}" x-ref="selectEl" x-on:change="$data.addItem()" multiple></select>
    </div>

    <template x-for="option in $data.selectedOptions" x-bind:key="`${option.value}`">
        <input type="hidden" name="{{ $name }}[]" x-bind:value="option.value" />
    </template>
</div>
