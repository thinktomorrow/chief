<button type="button" x-on:click="$dispatch('open-dialog', { 'id': '{{ $id }}-select' })">
    <x-chief-table::filter.select :id="$id" :value="$value">{{ $label }}</x-chief-table::filter.select>
</button>

<x-chief::dialog.dropdown id="#{{ $id }}-select" placement="bottom-end">
    <div class="space-y-3.5 p-3.5">
        <div class="space-y-2">
            @foreach ($options as $value => $label)
                <label for="{{ $id }}-{{ $value }}" class="flex items-start gap-2">
                    <x-chief::input.radio
                        wire:model.live.debounce.300ms="filters.{{ $name }}"
                        id="{{ $id }}-{{ $value }}"
                        name="{{ $name }}"
                        value="{{ $value }}"
                        :checked="in_array($value, (array) $value)"
                    />

                    <span class="body body-dark leading-5">{!! $label !!}</span>
                </label>
            @endforeach
        </div>

        <div class="flex items-start justify-between gap-2">
            <button type="button" x-on:click="close()">
                <x-chief-table::button size="sm" color="white">Annuleer</x-chief-table::button>
            </button>

            <button type="button">
                <x-chief-table::button size="sm" color="grey">Pas filter toe</x-chief-table::button>
            </button>
        </div>
    </div>
</x-chief::dialog.dropdown>
