<div class="flex max-w-80 flex-wrap items-start gap-1">
    @foreach ($getItems() as $badge)
        @php
            $value = $badge->getValue();
            $variant = $badge->getVariant();
        @endphp

        @if ($badge->hasLink())
            @if ($variant === 'green')
                <a href="{{ $badge->getLink() }}" class="badge badge-xs badge-green">{{ $value }}</a>
            @elseif ($variant === 'red')
                <a href="{{ $badge->getLink() }}" class="badge badge-xs badge-red">{{ $value }}</a>
            @elseif ($variant && str_starts_with($variant, '#'))
                <a href="{{ $badge->getLink() }}" class="badge badge-xs" style="background-color: {{ $variant }}">{{ $value }}</a>
            @else
                <a href="{{ $badge->getLink() }}" class="badge badge-xs badge-grey">{{ $value }}</a>
            @endif
        @else
            @if ($variant === 'green')
                <span class="badge badge-xs badge-green">{{ $value }}</span>
            @elseif ($variant === 'red')
                <span class="badge badge-xs badge-red">{{ $value }}</span>
            @elseif ($variant && str_starts_with($variant, '#'))
                <span class="badge badge-xs" style="background-color: {{ $variant }}">{{ $value }}</span>
            @else
                <span class="badge badge-xs badge-grey">{{ $value }}</span>
            @endif
        @endif

    @endforeach
</div>
