@php
    $id = 'button-group-' . mt_rand(0, 9999);
@endphp

<div
    wire:ignore
    x-cloak
    x-data="{
        activeRadio: null,
        init() {
            activeRadio = Array.from(this.$root.querySelectorAll('input')).find(
                (radio) => radio.checked,
            )

            if (this.activeRadio) {
                $nextTick(() => {
                    this.repositionOptionMarker(activeRadio.parentElement)
                })
            }
        },
        repositionOptionMarker(optionElement) {
            const radioInput = optionElement.querySelector('input')

            if (! radioInput.checked) return

            this.activeRadio = radioInput

            this.$refs.optionMarker.style.width = optionElement.offsetWidth + 'px'
            this.$refs.optionMarker.style.left = optionElement.offsetLeft + 'px'
        },
    }"
    class="rounded-[0.625rem] border-2 border-grey-100 bg-grey-100"
>
    <div class="relative flex items-start justify-start">
        <div
            x-ref="optionMarker"
            x-show="activeRadio"
            class="bui-btn bui-btn-base bui-btn-white absolute left-0 rounded-lg p-1.5 ring-grey-200 duration-300 ease-out"
        >
            <span class="bui-btn-content h-5"></span>
        </div>

        @foreach ($getOptions() as $option)
            <div wire:key="{{ $id }}-{{ $option['value'] }}" x-on:change="repositionOptionMarker($el)" class="relative">

                @if($loop->first)
                    <x-chief::input.radio
                        id="{{ $id }}-{{ $option['value'] }}"
                        value="{{ $option['value'] }}"
                        {{--                    :checked='true'--}}
                        :checked="!$this->getActiveFilterValue($getKey())"
                        {{--                    class="peer hidden"--}}
                    />

                    <label for="{{ $id }}-{{ $option['value'] }}" class="bui-btn bui-btn-base cursor-pointer rounded-lg p-1.5">
                        <span class="bui-btn-content">{!! $option['label'] !!}</span>
                    </label>
                @else
                    <x-chief::input.radio
                        wire:model.live.debounce.300ms="filters.{{ $getKey() }}"
                        id="{{ $id }}-{{ $option['value'] }}"
                        value="{{ $option['value'] }}"
                        {{--                    :checked='true'--}}
                        {{--                    :checked="$option['value'] === '' && (count($filters[$getKey()] ?? []) === 0)"--}}
                        {{--                    class="peer hidden"--}}
                    />

                    <label for="{{ $id }}-{{ $option['value'] }}" class="bui-btn bui-btn-base cursor-pointer rounded-lg p-1.5">
                        <span class="bui-btn-content">{!! $option['label'] !!}</span>
                    </label>
                @endif



            </div>
        @endforeach
    </div>
</div>
