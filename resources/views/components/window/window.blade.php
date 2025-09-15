@props([
    'title' => null,
    'description' => null,
    'variant' => 'card',
    'badges' => null,
    'actions' => null,
    'tabs' => null,
])

<div
    data-slot="window"
    data-variant="{{ $variant }}"
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
                'rounded-tr-xl rounded-b-xl' => $tabs,
                match ($variant) {
                    'card' => 'border-grey-100 shadow-grey-500/10 border bg-white p-4 shadow-md',
                    'transparent' => 'p-4',
                    default => '',
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
                                <h2 class="font-display text-grey-950 text-xl/6 font-semibold">
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
                    <div
                        wire:loading.remove.delay
                        {{ $actions->attributes->class(['ml-auto flex shrink-0 items-start gap-1.5']) }}
                    >
                        {!! $actions !!}
                    </div>
                @endif

                <div wire:loading.delay class="ml-auto shrink-0">
                    <x-chief::icon.loading class="text-grey-700 m-[0.3125rem] size-5 shrink-0 animate-spin" />
                </div>
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
