@props([
    'title' => null,
    'labels' => null,
    'actions' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'space-y-3']) }}>
    {{-- Window header --}}
    @if ($title || $labels || $actions || $description)
        <div class="flex items-start justify-between gap-4">
            @if ($title || $labels || $description)
                <div class="space-y-1.5">
                    <div class="flex items-start gap-1">
                        @if ($title)
                            <h2 class="mt-[0.1875rem] text-lg/6 font-medium text-grey-950">
                                {!! $title !!}
                            </h2>
                        @endif

                        @if ($labels)
                            <div class="flex items-center gap-1">
                                {!! $labels !!}
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
