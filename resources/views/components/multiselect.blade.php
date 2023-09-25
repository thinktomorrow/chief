@props([
  'options' => [],
  'selection' => [],
  'multiple' => false,
])

@once
    {{-- TODO: Tijs extract to our builds --}}
    @push('custom-styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    @endpush
    @push('custom-scripts')
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    @endpush
@endonce

<div
    x-cloak
    wire:ignore
    x-data="{
        selection: {{ json_encode((array) $selection) }},
        options: {{ json_encode($options) }},
        syncSelection: () => {},
    }"
    x-init="

    $nextTick(() => {

        const choices = new Choices($refs.selectEl, {
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
        });

        const refreshOptions = () => {

            // Reset all options
            choices.clearStore();
            choices.setChoices($data.options);

            // Set current value
            choices.setValue($data.selection);
        };

        $data.syncSelection = (e) => {

            const value = choices.getValue(true);
            $data.selection = Array.isArray(value) ? value : [value];

        }

        refreshOptions();

    });


">
    <select
        x-on:change="syncSelection()"
        {{ $attributes }}
        {{ $multiple ? 'multiple' : '' }}
        x-ref="selectEl"></select>
</div>
