@props([
    'size' => 'xs',
    'color' => null,
])

<span
    {{
        $attributes->class([
            'tag',
            match ($size) {
                'base' => 'tag-base',
                'sm' => 'tag-sm',
                'xs' => 'tag-xs',
                default => 'tag-xs',
            },
        ])
    }}
>
    @if ($color)
        <svg class="size-2" style="fill: {{ $color }}" viewBox="0 0 6 6" aria-hidden="true">
            <circle cx="3" cy="3" r="3" />
        </svg>
    @endif

    {{ $slot }}
</span>
