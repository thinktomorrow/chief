@php
    $id = 'checkbox-filter-' . $this->getId() . '-' . $getKey();
    $activeValue = (array) ($this->filters[$getKey()] ?? []);
@endphp

<div
    x-data="{
        selection: @js(array_map('strval', $activeValue)),
        syncSelection() {
            const value = $wire.filters[@js($getKey())] ?? []
            this.selection = (Array.isArray(value) ? value : [value]).map(String)
        },
    }"
    x-on:{{ $this->getFiltersUpdatedEvent() }}.window="syncSelection()"
>
    <button
        type="button"
        x-on:click="() => {
            syncSelection()
            $dispatch('open-dialog', { 'id': '{{ $id }}' })
        }"
    >
        <x-chief-table::filter.select>
            {{ $getLabel() ?? $getKey() }}

            @if ($this->getActiveFilterValue($getKey()))
                <div class="flex max-w-64 scrollbar-none items-start gap-1 overflow-x-auto">
                    <span class="text-grey-200">|</span>

                    <span class="text-primary-500 text-nowrap">{{ $this->getActiveFilterValue($getKey()) }}</span>
                </div>
            @endif
        </x-chief-table::filter.select>
    </button>

    <x-chief::dialog.dropdown id="{{ $id }}" placement="bottom-start">
        <div class="space-y-3.5 p-3">
            <div class="max-h-80 min-w-56 space-y-2 overflow-y-auto p-0.5">
                @foreach ($getOptions() as $option)
                    <label
                        wire:key="{{ $id }}-{{ $option['value'] }}"
                        for="{{ $id }}-{{ $option['value'] }}"
                        class="flex items-start gap-2"
                    >
                        <x-chief::form.input.checkbox
                            x-model="selection"
                            id="{{ $id }}-{{ $option['value'] }}"
                            value="{{ $option['value'] }}"
                            :checked="in_array((string) $option['value'], array_map('strval', $activeValue), true)"
                        />

                        <span class="body body-dark leading-5">{!! $option['label'] !!}</span>
                    </label>
                @endforeach
            </div>

            <div class="flex items-start justify-between gap-2">
                <x-chief::button
                    x-on:click="() => {
                        selection = []
                        close()
                        $wire.set('filters.{{ $getKey() }}', [])
                    }"
                    size="sm"
                    variant="grey"
                >
                    Wis selectie
                </x-chief::button>
                <x-chief::button
                    x-on:click="() => {
                        close()
                        $wire.set('filters.{{ $getKey() }}', selection)
                    }"
                    size="sm"
                    variant="blue"
                >
                    Pas filter toe
                </x-chief::button>
            </div>
        </div>
    </x-chief::dialog.dropdown>
</div>
