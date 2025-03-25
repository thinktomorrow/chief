@props([
    'size' => 'base',
])

<span
    {{
        $attributes->class([
            'bui-btn bg-white font-normal text-grey-500 shadow-sm ring-1 ring-inset ring-grey-200 focus-within:text-grey-800 focus-within:ring-grey-300 hover:ring-grey-300',
            'bui-btn-base' => $size === 'base',
            'bui-btn-sm' => $size === 'sm',
            'bui-btn-xs' => $size === 'xs',
        ])
    }}
>
    {{ $slot }}
</span>
