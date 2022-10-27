@props([
    'title' => null,
    'labels' => null,
    'buttons' => null,
])

<div {{ $attributes->merge(['class' => 'space-y-6']) }}>
    {{-- Window header --}}
    @if($title || $labels || $buttons)
        <div class="flex justify-end space-x-4">
            <div class="w-full space-x-1">
                @if($title)
                    <span class="text-lg display-base display-dark">
                        {!! $title !!}
                    </span>
                @endif

                @if($labels)
                    <span class="align-bottom with-xs-labels">
                        {!! $labels !!}
                    </span>
                @endif
            </div>

            @if($buttons)
                <div class="shrink-0">
                    {!! $buttons !!}
                </div>
            @endif
        </div>
    @endif

    {{-- Window content --}}
    @if($slot->isNotEmpty())
        <div>
            {!! $slot !!}
        </div>
    @endisset
</div>
