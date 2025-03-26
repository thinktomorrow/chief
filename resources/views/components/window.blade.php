@props([
    'title' => null,
    'badges' => null,
    'actions' => null,
    'description' => null,
    'variant' => 'card',
])

<div
    {{
        $attributes->class([
            'space-y-3',
            match ($variant) {
                'white' => 'rounded-xl bg-white p-4 shadow-md shadow-grey-500/10 ring-1 ring-grey-200',
                'transparent' => '',
                default => 'rounded-xl bg-white p-4 shadow-md shadow-grey-500/10 ring-1 ring-grey-100',
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
                <div class="shrink-0">
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
