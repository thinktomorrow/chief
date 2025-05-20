@props([
    'size' => 'base',
])

<span
    {{
        $attributes->class([
            'btn text-grey-500 ring-grey-200 focus-within:text-grey-800 focus-within:ring-grey-300 hover:ring-grey-300 bg-white font-normal shadow-xs ring-1 ring-inset',
            'btn-base' => $size === 'base',
            'btn-sm' => $size === 'sm',
            'btn-xs' => $size === 'xs',
        ])
    }}
>
    {{ $slot }}
</span>
