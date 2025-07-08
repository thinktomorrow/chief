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
    {{ $attributes->merge(['data-slot' => 'control'])->class(['space-y-2']) }}
>
    <div
        x-sortable
        x-sortable-ghost-class="select-list-ghost"
        x-sortable-drag-class="select-list-drag"
        x-on:end.stop="
            () => {
                $data.sortSelection($event.target.sortable.toArray())
            }
        "
        class="flex flex-wrap items-start gap-1"
    >
        <template x-for="(option, index) in $data.selectedOptions" x-bind:key="`${option.value}-${index}`">
            <x-chief::badge
                x-sortable-handle
                x-bind:x-sortable-item="option.value"
                size="base"
                @class([
                    'inline-flex items-start gap-1 border border-transparent cursor-grab',
                    '[&.select-list-ghost]:border-dashed',
                    '[&.select-list-ghost]:border-primary-500',
                    '[&.select-list-ghost]:bg-primary-50',
                    '[&.select-list-ghost]:shadow',
                    '[&.select-list-ghost]:shadow-primary-50',
                    '[&.select-list-drag]:opacity-50',
                ])
            >
                <span x-html="option.label" class="grow"></span>

                <button type="button" x-on:click="$data.removeItem(option.value)" class="shrink-0">
                    <x-chief::icon.cancel class="hover:body-dark text-grey-400 my-0.5 size-4" />
                </button>
            </x-chief::badge>
        </template>

        <button
            type="button"
            x-show="$data.allowSelectBox && !$data.showingSelectBox"
            x-on:click="$data.showSelectBox()"
            class="origin-center border border-transparent"
        >
            <x-chief::badge size="base">
                <x-chief::icon.plus-sign class="hover:body-dark text-grey-400 my-0.5 size-4" />
            </x-chief::badge>
        </button>
    </div>

    <div x-show="$data.showingSelectBox">
        <select name="{{ $name }}" x-ref="selectEl" x-on:change="$data.addItem()" multiple></select>
    </div>

    <template x-for="option in $data.selectedOptions" x-bind:key="`${option.value}`">
        <input type="hidden" name="{{ $name }}[]" x-bind:value="option.value" />
    </template>
</div>
