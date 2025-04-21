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
        'rounded-[0.625rem] bg-grey-100',
    ])
>
    <div class="relative flex items-start justify-start border border-transparent">
        <div
            x-ref="optionMarker"
            x-show="activeRadio"
            class="btn btn-base btn-outline-white absolute left-0 rounded-[0.5625rem] py-[0.4375rem] font-normal ring-0 transition-all duration-150 ease-out"
        >
            <span class="h-5"></span>
        </div>

        @foreach ($getOptions() as $option)
            <div
                wire:key="{{ $id }}-{{ $option['value'] }}"
                x-on:change="repositionOptionMarker($el)"
                class="relative"
            >
                <x-chief::form.input.radio
                    wire:model.change="filters.{{ $getKey() }}"
                    id="{{ $id }}-{{ $option['value'] }}"
                    value="{{ $option['value'] }}"
                    class="peer hidden"
                />

                <label
                    for="{{ $id }}-{{ $option['value'] }}"
                    class="btn btn-base cursor-pointer py-[0.4375rem] font-normal text-grey-800 shadow-none peer-checked:text-grey-950"
                >
                    {!! $option['label'] !!}
                </label>
            </div>
        @endforeach
    </div>
</div>
