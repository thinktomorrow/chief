<x-chief-table::filter.text>
    <x-chief::icon.search />

    <input
        type="text"
        wire:model.live.debounce.500ms="filters.{{ $getKey() }}"
        placeholder="{{ $getPlaceholder() }}"
        class="w-full text-grey-800 placeholder:text-grey-500"
    />
</x-chief-table::filter.text>
