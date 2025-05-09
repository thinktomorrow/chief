@props([
    'size' => 'base',
])

<span
    {{
        $attributes->class([
            'btn bg-white font-normal text-grey-500 shadow-sm ring-1 ring-inset ring-grey-200 focus-within:text-grey-800 focus-within:ring-grey-300 hover:ring-grey-300',
            'btn-base' => $size === 'base',
            'btn-sm' => $size === 'sm',
            'btn-xs' => $size === 'xs',
        ])
    }}
>
    {{ $slot }}
</span>
