@php
    // Nav items with this attribute will be open on page load.
    $open = $attributes->has('open');
    $blank = $attributes->has('blank');
    $dropdownIdentifier = uniqid();
@endphp

<div x-data="{ isOpen: false }" class="group">
    <div class="cursor-pointer rounded-lg hover:bg-grey-100">
        <div class="flex items-start justify-between gap-3 px-2">
            <div class="flex grow gap-2">
                @isset($icon)
                    @isset($url)
                        <a
                            href="{{ $url }}"
                            title="{!! $label !!}"
                            class="shrink-0 py-1.5 [&>*]:h-6 [&>*]:w-6 [&>*]:text-grey-400 group-hover:[&>*]:text-primary-500"
                            {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                        >
                            {!! $icon !!}
                        </a>
                    @else
                        <div
                            class="shrink-0 py-1.5 [&>*]:h-6 [&>*]:w-6 [&>*]:text-grey-400 group-hover:[&>*]:text-primary-500"
                        >
                            {!! $icon !!}
                        </div>
                    @endisset
                @endisset

                @isset($url)
                    <a
                        href="{{ $url }}"
                        title="{!! $label !!}"
                        class="inline-block w-full py-1.5 text-sm/6 text-grey-700 group-hover:text-grey-950"
                        {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                    >
                        {!! $label !!}
                    </a>
                @else
                    <span class="inline-block w-full py-1.5 text-sm/6 text-grey-700 group-hover:text-grey-950">
                        {!! $label !!}
                    </span>
                @endisset
            </div>

            @if (! $slot->isEmpty())
                <x-chief::button
                    type="button"
                    size="sm"
                    variant="transparent"
                    x-show="!isOpen"
                    x-on:click="isOpen = true"
                    class="mt-[0.1875rem] shrink-0"
                >
                    <x-chief::icon.chevron-left />
                </x-chief::button>

                <x-chief::button
                    type="button"
                    size="sm"
                    variant="transparent"
                    x-show="isOpen"
                    x-on:click="isOpen = false"
                    class="mt-[0.1875rem] shrink-0"
                >
                    <x-chief::icon.chevron-down />
                </x-chief::button>
            @endif
        </div>
    </div>

    @if (! $slot->isEmpty())
        <div x-show="isOpen" class="ml-8">
            {!! $slot !!}
        </div>
    @endif
</div>
