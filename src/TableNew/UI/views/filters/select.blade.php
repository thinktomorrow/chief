<button id="{{ $id }}-select" type="button">
    <x-chief-table-new::filter.select :id="$id" :label="$label" :value="$value" />
</button>

<x-chief::dropdown trigger="#{{ $id }}-select" placement="bottom-end">
    <div class="space-y-2.5 p-3.5">
        <x-chief::multiselect
            wire:model.live.debounce.300ms="filters.{{ $name }}"
            :options='$options'
            :selection='$value ?: $default'
            :multiple='$multiple'
            class="w-64"
        />

        <div class="flex items-start justify-between gap-2">
            <button type="button" x-on:click="close()">
                <x-chief-table-new::button size="sm" color="white">Annuleer</x-chief-table-new::button>
            </button>

            <button type="button">
                <x-chief-table-new::button size="sm" color="grey">Pas filter toe</x-chief-table-new::button>
            </button>
        </div>
    </div>
</x-chief::dropdown>
