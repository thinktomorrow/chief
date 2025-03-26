@props([
    'title' => null,
    'description' => null,
    'variant' => 'card',
    'badges' => null,
    'actions' => null,
    'tabs' => null,
])

<div
    @class([
        '-space-y-px',
        '[&>[data-slot=window-tabs]:has(:first-child[data-slot=inactive-tab])+[data-slot=window-content]]:rounded-tl-xl',
    ])
>
    @if ($tabs)
        <div {{
            $tabs->attributes->merge([
                'data-slot' => 'window-tabs',
            ])
        }}>
            {{ $tabs }}
        </div>
    @endif

    <div
        data-slot="window-content"
        {{
            $attributes->class([
                'space-y-3',
                'rounded-xl' => ! $tabs,
                'rounded-b-xl rounded-tr-xl' => $tabs,
                match ($variant) {
                    'white' => 'border border-grey-100 bg-white p-4 shadow-md shadow-grey-500/10',
                    'transparent' => '',
                    default => 'border border-grey-100 bg-white p-4 shadow-md shadow-grey-500/10',
                },
            ])
        }}
    >
        {{-- Window header --}}
        @if ($title || $badges || $actions || $description)
            <div class="flex items-start justify-between gap-4">
                @if ($title || $badges || $description)
                    <div class="space-y-1.5">
                        <div class="mt-[0.1875rem] flex items-start gap-2">
                            @if ($title)
                                <h2 class="text-lg/6 font-medium text-grey-950">
                                    {!! $title !!}
                                </h2>
                            @endif

                            @if ($badges)
                                <div class="flex items-center gap-1">
                                    {!! $badges !!}
                                </div>
                            @endif
                        </div>

                        @if ($description)
                            <p class="body text-grey-500">
                                {!! $description !!}
                            </p>
                        @endif
                    </div>
                @endif

                @if ($actions)
                    <div {{ $actions->attributes->class(['flex shrink-0 items-start gap-1.5']) }}>
                        {!! $actions !!}
                    </div>
                @endif
            </div>
        @endif

        {{-- Window content --}}
        @if ($slot->isNotEmpty())
            <div>
                {!! $slot !!}
            </div>
        @endif
    </div>
</div>
