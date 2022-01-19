@if($getTitle() || $getDescription())
    <div class="space-y-1">
        @if($getTitle())
            <span class="text-lg display-dark display-base">{{ $getTitle() }}</span>
        @endif

        @if($getDescription())
            <div class="prose prose-dark">
                {!! $getDescription()!!}
            </div>
        @endif
    </div>
@endif
