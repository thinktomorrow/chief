<x-chief-table-new::filter.text>
    <input
        type="text"
        wire:model.live.debounce.300ms="filters.{{ $name }}"
        placeholder="{{ $placeholder }}"
        class="w-48"
    />
</x-chief-table-new::filter.text>
