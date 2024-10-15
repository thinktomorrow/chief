<div class="flex max-w-80 flex-wrap items-start gap-1">
    @foreach ($getValues() as $badge)
        @php
            $value = $badge->getValue();
            $type = $badge->getType($value);
        @endphp

        @if ($type === 'green')
            <span class="badge badge-xs badge-green">{{ $value }}</span>
        @elseif ($type === 'red')
            <span class="badge badge-xs badge-red">{{ $value }}</span>
        @elseif ($type && str_starts_with($type, '#'))
            <span class="badge badge-xs" style="background-color: {{ $type }}">{{ $value }}</span>
        @else
            <span class="badge badge-xs badge-grey">{{ $value }}</span>
        @endif
    @endforeach
</div>
