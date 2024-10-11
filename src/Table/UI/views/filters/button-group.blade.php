@php
    $id = 'button-group-' . mt_rand(0, 9999);
@endphp

<div
    wire:ignore
    x-cloak
    x-data="{
        activeRadio: null,
        repositionOptionMarker(optionElement) {
            const radioInput = optionElement.querySelector('input')

            if (! radioInput.checked) return

            this.activeRadio = radioInput

            this.$refs.optionMarker.style.width = optionElement.offsetWidth + 'px'
            this.$refs.optionMarker.style.left = optionElement.offsetLeft + 'px'
        },
        repositionCheckedOptionMarker() {
            $nextTick(() => {
                const activeRadio = Array.from(
                    this.$root.querySelectorAll('input'),
                ).find((radio) => radio.checked)

                if (activeRadio) {
                    this.repositionOptionMarker(activeRadio.parentElement)
                }
            })
        },
        init() {
            this.repositionCheckedOptionMarker()
        },
    }"
    x-on:{{ $this->getFiltersUpdatedEvent() }}.window="repositionCheckedOptionMarker"
    x-on:dialog-opened.window="repositionCheckedOptionMarker"
    @class([
        'ring-bui-grey-950/5 bg-bui-grey-950/5 rounded-[0.625rem] ring-1',
    ])
>
    <div class="relative flex items-start justify-start">
        <div
            x-ref="optionMarker"
            x-show="activeRadio"
            class="bui-btn bui-btn-base bui-btn-tertiary absolute left-0 rounded-[0.5625rem] transition-all duration-150 ease-out"
        >
            <span class="h-5"></span>
        </div>

        @foreach ($getOptions() as $option)
            <div
                wire:key="{{ $id }}-{{ $option['value'] }}"
                x-on:change="repositionOptionMarker($el)"
                class="relative"
            >
                <x-chief::input.radio
                    wire:model.change="filters.{{ $getKey() }}"
                    id="{{ $id }}-{{ $option['value'] }}"
                    value="{{ $option['value'] }}"
                    class="peer hidden"
                />

                <label
                    for="{{ $id }}-{{ $option['value'] }}"
                    class="bui-btn bui-btn-base cursor-pointer text-grey-800"
                >
                    {!! $option['label'] !!}
                </label>
            </div>
        @endforeach
    </div>
</div>
