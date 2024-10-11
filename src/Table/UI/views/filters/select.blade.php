@php
    $triggerId = 'js-trigger-' . mt_rand(0, 9999);
@endphp

<button type="button" x-on:click="$dispatch('open-dialog', { 'id': '{{ $triggerId }}' })">
    <x-chief-table::filter.select>
        {{ $getLabel() ?? $getKey() }}

        @if ($this->getActiveFilterValue($getKey()))
            <span class="text-grey-200">|</span>

            <span class="text-nowrap text-primary-500">{{ $this->getActiveFilterValue($getKey()) }}</span>
        @endif
    </x-chief-table::filter.select>
</button>

<x-chief::dialog.dropdown id="{{ $triggerId }}" placement="bottom-end">
    <div class="space-y-2.5 p-3.5">
        <x-chief::multiselect
            wire:model="filters.{{ $getKey() }}"
            :options='$getMultiSelectFieldOptions()'
            :selection='$getValue() ?: $getDefault()'
            :multiple='$allowMultiple()'
            class="w-64"
        />

        <div class="flex items-start justify-between gap-2">
            <button type="button" x-on:click="close()">
                <x-chief-table::button size="sm" variant="secondary">Annuleer</x-chief-table::button>
            </button>

            <button
                type="submit"
                x-on:click="
                    close()
                    $wire.addFilter()
                "
            >
                <x-chief-table::button size="sm" variant="primary">Pas filter toe</x-chief-table::button>
            </button>
        </div>
    </div>
</x-chief::dialog.dropdown>
