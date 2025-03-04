@props([
    'title' => null,
    'labels' => null,
    'buttons' => null,
])

<div {{ $attributes->merge(['class' => 'space-y-4']) }}>
    {{-- Window header --}}
    @if ($title || $labels || $buttons)
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-1">
                @if ($title)
                    <h2 class="mt-[0.1875rem] text-lg/6 font-medium text-grey-950">
                        {!! $title !!}
                    </h2>
                @endif

                @if ($labels)
                    <span class="with-xs-labels align-bottom">
                        {!! $labels !!}
                    </span>
                @endif
            </div>

            @if ($buttons)
                <div class="shrink-0">
                    {!! $buttons !!}
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
