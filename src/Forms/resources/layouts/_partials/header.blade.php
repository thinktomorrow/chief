@if ($getTitle() || $getDescription())
    <div class="w-full space-y-1">
        @if ($getTitle())
            <span class="text-sm font-medium tracking-wider uppercase body-dark">{{ $getTitle() }}</span>
        @endif

        @if ($getDescription())
            <p class="text-sm body text-grey-500">{!! $getDescription() !!}</p>
        @endif
    </div>
@endif
