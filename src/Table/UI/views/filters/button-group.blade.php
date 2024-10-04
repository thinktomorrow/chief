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
    class="rounded-[0.625rem] border-2 border-grey-100 bg-grey-100"
>
    <div class="relative flex items-start justify-start">
        <div
            x-ref="optionMarker"
            x-show="activeRadio"
            class="bui-btn bui-btn-sm bui-btn-white absolute left-0 py-1.5 transition-all duration-150 ease-out"
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
                    class="bui-btn bui-btn-sm cursor-pointer py-1.5 text-base/5 text-grey-800"
                >
                    {!! $option['label'] !!}
                </label>
            </div>
        @endforeach
    </div>
</div>
