@props([
    'size' => 'base',
])

<span
    @class([
        'btn text-grey-800 ring-grey-200 hover:ring-grey-300 bg-white font-normal shadow-xs ring-1 ring-inset',
        'btn-base' => $size === 'base',
        'btn-sm' => $size === 'sm',
        'btn-xs' => $size === 'xs',
    ])
>
    <span class="flex items-start gap-1">
        {{ $slot }}
    </span>

    <x-chief::icon.chevron-down class="text-grey-500" />
</span>
