@php
    // Nav items with this attribute will be open on page load.
    $open = $attributes->has('open');
    $blank = $attributes->has('blank');
    $dropdownIdentifier = uniqid();
@endphp

<div x-data="{ isOpen: false }" class="group">
    <div class="cursor-pointer rounded-lg hover:bg-grey-100">
        <div class="flex justify-between gap-3 px-2">
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
                        class="inline-block w-full py-1.5 text-sm/6 text-grey-700 group-hover:text-grey-950 lg:w-36"
                        {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                    >
                        {!! $label !!}
                    </a>
                @else
                    <span class="inline-block w-full py-1.5 text-sm/6 text-grey-700 group-hover:text-grey-950 lg:w-36">
                        {!! $label !!}
                    </span>
                @endisset
            </div>

            @if (! $slot->isEmpty())
                <div class="mt-2.5 shrink-0">
                    <div x-on:click="isOpen = !isOpen" class="flex items-center justify-center">
                        <x-chief::icon.chevron-left class="size-4 text-grey-700" x-show="!isOpen" />
                        <x-chief::icon.chevron-down class="size-4 text-grey-700" x-show="isOpen" />
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if (! $slot->isEmpty())
        <div x-show="isOpen" class="ml-8">
            {!! $slot !!}
        </div>
    @endif
</div>
