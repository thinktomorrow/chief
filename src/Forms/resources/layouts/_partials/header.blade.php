@if($getTitle() || $getDescription())
    <div class="w-full space-y-1">
        @if($getTitle())
            <span class="text-sm tracking-wider uppercase h6 body-dark">{{ $getTitle() }}</span>
        @endif

        @if($getDescription())
            <div class="prose prose-spacing prose-dark">
                <p>{!! $getDescription() !!}</p>
            </div>
        @endif
    </div>
@endif
