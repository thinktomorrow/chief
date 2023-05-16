@props([
    'color' => null,
    'size' => 'xs',
])

<span {{ $attributes->class([
    'inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 font-medium body-dark ring-1 ring-inset ring-grey-200 hover:ring-grey-400',
    'text-xs' => $size === 'xs',
    'text-sm' => $size === 'sm',
]) }}>
    @if ($color)
        <svg class="w-2 h-2" style="fill: {{ $color }};" viewBox="0 0 6 6" aria-hidden="true">
            <circle cx="3" cy="3" r="3" />
        </svg>
    @endif

    {{ $slot }}
</span>
