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
    selection: {{ json_encode($selection) }},
    options: {{ json_encode($options) }},
    syncSelection: () => {},
}" x-init="

    $nextTick(() => {

        const choices = new Choices($refs.selectEl, {
            allowHTML: true,
            removeItems: true,
            removeItemButton: true,
            duplicateItemsAllowed: false,
            noResultsText: 'Geen resultaten',
            noChoicesText: 'geen opties',
            itemSelectText: '',
            uniqueItemText: 'enkel unieke opties zijn mogelijk',
        });

        const refreshOptions = () => {

            choices.clearStore();

            choices.setChoices($data.options.map( ({value, label}) => ({
                value,
                label,
                selected: $data.selection.includes(value)
            })));
        };

        $data.syncSelection = (e) => {

            $data.selection = Array.isArray(choices.getValue(true)) ? choices.getValue(true) : [choices.getValue(true)];

        }

        refreshOptions();

    });


">
    <select
        {{ $attributes }}
        x-on:change="syncSelection()"
        multiple
        x-ref="selectEl"></select>
</div>
