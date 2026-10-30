@props ([
    'label',
    'icon' => null,
    'url' => null,
    'open' => false,
    'isActive' => false,
])

@php
    // Nav items with this attribute will be open on page load.
    $open = $open ?? false;
    $blank = $attributes->has('blank');
    $dropdownIdentifier = uniqid();
@endphp

<div x-data="{ isOpen: @js($open) }" class="group">
    <div
        @class ([
            'hover:bg-grey-100 cursor-pointer rounded-lg',
            'bg-grey-100' => $isActive ?? false,
            'before:bg-primary-500 relative before:absolute before:inset-y-1.5 before:left-[var(--nav-active-offset,-0.375rem)] before:w-0.5 before:rounded-full' => $isActive,
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
                                '[&>*]:text-grey-400 group-hover:[&>*]:text-primary-500 shrink-0 py-1 [&>*]:h-6 [&>*]:w-6',
                                '[&>*]:text-primary-500' => $isActive ?? false,
                            ])
                            {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                        >
                            {!! $icon !!}
                        </a>
                    @else
                        <div
                            @class ([
                                '[&>*]:text-grey-400 group-hover:[&>*]:text-primary-500 shrink-0 py-1 [&>*]:h-6 [&>*]:w-6',
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
                            'text-grey-700 group-hover:text-grey-950 inline-block w-full py-1.5 text-sm/5',
                            'text-grey-950' => $isActive ?? false,
                        ])
                        {!! $blank ? 'target="_blank" rel="noopener"' : null !!}
                    >
                        {!! $label !!}
                    </a>
                @else
                    <span
                        @class ([
                            'text-grey-700 group-hover:text-grey-950 inline-block w-full py-1.5 text-sm/5',
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
                    size="xs"
                    variant="transparent"
                    x-show="!isOpen"
                    x-on:click="isOpen = true"
                    class="mt-1 -mr-1 shrink-0"
                >
                    <x-chief::icon.chevron-left />
                </x-chief::button>

                <x-chief::button
                    type="button"
                    size="xs"
                    variant="transparent"
                    x-cloak
                    x-show="isOpen"
                    x-on:click="isOpen = false"
                    class="mt-1 -mr-1 shrink-0"
                >
                    <x-chief::icon.chevron-down />
                </x-chief::button>
            @endif
        </div>
    </div>

    @if (! $slot->isEmpty())
        {{-- The guide line hangs from the parent's icon, or from its label when it has none; nested active bars sit on it. --}}
        <div
            x-cloak
            x-show="isOpen"
            @class ([
                'border-grey-200 border-l',
                'ml-5 pl-2.75 [--nav-active-offset:-12.5px]' => filled($icon),
                'ml-2 pl-2 [--nav-active-offset:-9.5px]' => ! filled($icon),
            ])
        >
            {!! $slot !!}
        </div>
    @endif
</div>
