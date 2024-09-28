<x-chief-table::filter.text>
    <input
        type="text"
        wire:model.live.debounce.500ms="filters.{{ $getKey() }}"
        placeholder="{{ $getPlaceholder() }}"
        class="w-full"
    />
</x-chief-table::filter.text>
