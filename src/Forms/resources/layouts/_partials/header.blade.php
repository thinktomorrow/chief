@if($getTitle() || $getDescription())
    <div class="w-full space-y-1">
        @if($getTitle())
            <span class="text-lg display-base display-dark">{{ $getTitle() }}</span>
        @endif

        @if($getDescription())
            <div class="prose prose-dark">
                <p> {!! $getDescription()!!} </p>
            </div>
        @endif
    </div>
@endif
