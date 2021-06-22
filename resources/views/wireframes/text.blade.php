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
        class="w-full prose prose-dark prose-editor prose-wireframe {{ $alignClass }} {{ $attributes->get('class') }}"
        {{-- Line clamp based on tailwindcss plugin --}}
        style="
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: {{ $lines }};
            {{ $attributes->get('style') }}
        "
    >
        {{ $slot }}
    </div>
@else
    <div class="w-full space-y-2 {{ $alignClass }} {{ $attributes->get('class') }}" style="{{ $attributes->get('style') }}">
        @for ($i = 0; $i < $lines; $i++)
            @if($i == $lines - 1 && $i > 0)
                <div class="inline-block w-1/2 rounded bg-grey-500" style="height: 6px;"></div>
            @else
                <div class="inline-block w-full rounded bg-grey-500" style="height: 6px;"></div>
            @endif
        @endfor
    </div>
@endunless
