@php
    $lines = $lines ?? 3;

    switch($align ?? null) {
        case 'left':
            $alignClass = 'text-left'; break;
        case 'center':
            $alignClass = 'text-center'; break;
        case 'right':
            $alignClass = 'text-right'; break;
        default:
            $alignClass = 'text-left';
    }
@endphp

@unless($slot->isEmpty())
    <div
        class="prose prose-dark prose-editor prose-wireframe {{ $alignClass }}"
        {{-- Line clamp based on tailwindcss plugin --}}
        style="
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: {{ $lines }};
        "
    >
        {{ $slot }}
    </div>
@else
    <div class="space-y-2 {{ $alignClass }}">
        @for ($i = 0; $i < $lines; $i++)
            @if($i == $lines - 1 && $i > 0)
                <div class="inline-block w-1/2 rounded bg-grey-500" style="height: 6px;"></div>
            @else
                <div class="inline-block w-full rounded bg-grey-500" style="height: 6px;"></div>
            @endif
        @endfor
    </div>
@endunless
