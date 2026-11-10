@php
    $id = 'radio-filter-' . $this->getId() . '-' . $getKey();
    $activeValue = $this->filters[$getKey()] ?? null;
@endphp

<div
    x-data="{
        selection: @js(is_null($activeValue) ? null : (string) $activeValue),
        syncSelection() {
            const value = $wire.filters[@js($getKey())] ?? null
            this.selection = value === null ? null : String(value)
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
                        <x-chief::form.input.radio
                            x-model="selection"
                            id="{{ $id }}-{{ $option['value'] }}"
                            name="{{ $id }}"
                            value="{{ $option['value'] }}"
                            :checked="! is_null($activeValue) && (string) $option['value'] === (string) $activeValue"
                        />

                        <span class="body body-dark leading-5">{!! $option['label'] !!}</span>
                    </label>
                @endforeach
            </div>

            <div class="flex items-start justify-between gap-2">
                <x-chief::button
                    x-on:click="() => {
                        selection = null
                        close()
                        $wire.set('filters.{{ $getKey() }}', null)
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
