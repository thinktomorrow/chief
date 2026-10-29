@props ([
    'label',
    'icon' => null,
    'url' => null,
    'open' => false,
    'isActive' => false,
    'compact' => false,
])

@php
    // Nav items with this attribute will be open on page load.
    $open = $open ?? false;
    $blank = $attributes->has('blank');
    $dropdownIdentifier = uniqid();
    $verticalPadding = $compact ? 'py-1' : 'py-1.5';
    $toggleOffset = $compact ? 'mt-px' : 'mt-[0.1875rem]';
@endphp

<div x-data="{ isOpen: @js($open) }" class="group">
    <div
        @class ([
            'hover:bg-grey-100 cursor-pointer rounded-lg',
            'bg-grey-100' => $isActive ?? false,
            'before:bg-primary-500 relative before:absolute before:inset-y-1.5 before:-left-1.5 before:w-0.5 before:rounded-full' => $compact && $isActive,
        ])
    >
        <div class="flex items-start justify-between gap-3 px-2">
            <div class="flex grow gap-2">
                @isset ($icon)
                    @isset ($url)
                        <a
                            href="{{ $url }}"
                            title="{!! $label !!}"
                            @class ([
                                '[&>*]:text-grey-400 group-hover:[&>*]:text-primary-500 shrink-0 [&>*]:h-6 [&>*]:w-6',
                                $verticalPadding,
                                '[&>*]:text-primary-500' => $isActive ?? false,
                            ])
                            {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                        >
                            {!! $icon !!}
                        </a>
                    @else
                        <div
                            @class ([
                                '[&>*]:text-grey-400 group-hover:[&>*]:text-primary-500 shrink-0 [&>*]:h-6 [&>*]:w-6',
                                $verticalPadding,
                                '[&>*]:text-primary-500' => $isActive ?? false,
                            ])
                        >
                            {!! $icon !!}
                        </div>
                    @endisset
                @endisset

                @isset ($url)
                    <a
                        href="{{ $url }}"
                        title="{!! $label !!}"
                        @class ([
                            'text-grey-700 group-hover:text-grey-950 inline-block w-full text-sm/6',
                            $verticalPadding,
                            'text-grey-950' => $isActive ?? false,
                        ])
                        {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                    >
                        {!! $label !!}
                    </a>
                @else
                    <span
                        @class ([
                            'text-grey-700 group-hover:text-grey-950 inline-block w-full text-sm/6',
                            $verticalPadding,
                            'text-grey-950' => $isActive ?? false,
                        ])
                    >
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
                    @class(['shrink-0', $toggleOffset])
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
                    @class(['shrink-0', $toggleOffset])
                >
                    <x-chief::icon.chevron-down />
                </x-chief::button>
            @endif
        </div>
    </div>

    @if (! $slot->isEmpty())
        <div x-cloak x-show="isOpen" @class ([$compact ? 'ml-4' : 'ml-8'])>{!! $slot !!}</div>
    @endif
</div>
