@if($getTitle() || $getDescription())
    <div class="w-full space-y-1">
        @if($getTitle())
            <span class="display-base display-dark">{{ $getTitle() }}</span>
        @endif

        @if($getDescription())
            <div class="prose prose-spacing prose-dark">
                @if($getDescription() != strip_tags($getDescription()))
                    {!! $getDescription() !!}
                @else
                    <p>{{ $getDescription() }}</p>
                @endif
            </div>
        @endif
    </div>
@endif
