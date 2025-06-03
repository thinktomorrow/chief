@php
    // Nav items with this attribute will be open on page load.
    $open = $open ?? false;
    $blank = $attributes->has('blank');
    $dropdownIdentifier = uniqid();
@endphp

<div x-data="{ isOpen: @js($open) }" class="group">
    <div
        @class([
            'hover:bg-grey-100 cursor-pointer rounded-lg',
            'bg-grey-100' => $isActive ?? false,
        ])>
        <div class="flex items-start justify-between gap-3 px-2">
            <div class="flex grow gap-2">
                @isset($icon)
                    @isset($url)
                        <a
                            href="{{ $url }}"
                            title="{!! $label !!}"
                            @class([
                                '[&>*]:text-grey-400 group-hover:[&>*]:text-primary-500 shrink-0 py-1.5 [&>*]:h-6 [&>*]:w-6',
                                '[&>*]:text-primary-500' => $isActive ?? false,
                            ])
                            {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                        >
                            {!! $icon !!}
                        </a>
                    @else
                        <div
                            @class([
                                '[&>*]:text-grey-400 group-hover:[&>*]:text-primary-500 shrink-0 py-1.5 [&>*]:h-6 [&>*]:w-6',
                                '[&>*]:text-primary-500' => $isActive ?? false,
                            ])
                        >
                            {!! $icon !!}
                        </div>
                    @endisset
                @endisset

                @isset($url)
                    <a
                        href="{{ $url }}"
                        title="{!! $label !!}"
                        @class([
                            'text-grey-700 group-hover:text-grey-950 inline-block w-full py-1.5 text-sm/6',
                            'text-grey-950' => $isActive ?? false,
                        ])
                        {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                    >
                        {!! $label !!}
                    </a>
                @else
                    <span
                    @class([
                        'text-grey-700 group-hover:text-grey-950 inline-block w-full py-1.5 text-sm/6',
                        'text-grey-950' => $isActive ?? false,
                    ])>
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
                    x-cloak
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
        <div x-cloak x-show="isOpen" class="ml-8">
            {!! $slot !!}
        </div>
    @endif
</div>
