<button type="button" x-on:click="$dispatch('open-dialog', { 'id': '{{ $id }}-select' })">
    <x-chief-table::filter.select :id="$id" :value="$value">
        {{ $label }}

        @if ($value)
            <span class="text-grey-200">|</span>

            <span class="text-nowrap text-primary-500">{{ $value }}</span>
        @endif
    </x-chief-table::filter.select>
</button>

<x-chief::dialog.dropdown id="#{{ $id }}-select" placement="bottom-start">
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
            <x-chief-table::button x-on:click="close()" size="sm" variant="grey">Annuleer</x-chief-table::button>
            <x-chief-table::button
                x-on:click="() => {
                    close()
                    $wire.addFilter()
                }"
                size="sm"
                variant="blue"
            >
                Pas filter toe
            </x-chief-table::button>
        </div>
    </div>
</x-chief::dialog.dropdown>
