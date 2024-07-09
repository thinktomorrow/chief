<x-chief-table-new::filter.text>
    <input
        type="text"
        wire:model.live.debounce.500ms="filters.{{ $getKey() }}"
        placeholder="{{ $getPlaceholder() }}"
        class="w-48"
    />
</x-chief-table-new::filter.text>
