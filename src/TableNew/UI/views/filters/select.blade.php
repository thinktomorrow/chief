@php
    $triggerId = 'js-trigger-' . mt_rand(0, 9999);
@endphp

<button type="button" x-on:click="$dispatch('open-dialog', { 'id': '{{ $triggerId }}' })">
    <x-chief-table-new::filter.select :value="$this->getActiveFilterValue($getKey())">
        {{ $getLabel() ?? $getKey() }}
    </x-chief-table-new::filter.select>
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
                <x-chief-table-new::button size="sm" color="white">Annuleer</x-chief-table-new::button>
            </button>

            <button
                type="submit"
                x-on:click="
                    close()
                    $wire.addFilter()
                "
            >
                <x-chief-table-new::button size="sm" color="grey">Pas filter toe</x-chief-table-new::button>
            </button>
        </div>
    </div>
</x-chief::dialog.dropdown>
