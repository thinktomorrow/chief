@props([
    'size' => 'base',
])

<span
    {{
        $attributes->class([
            'bui-btn bg-white font-normal text-grey-500 shadow-sm ring-1 ring-inset ring-grey-100 focus-within:text-grey-800 focus-within:ring-grey-200 hover:ring-grey-200',
            'bui-btn-base' => $size === 'base',
            'bui-btn-sm' => $size === 'sm',
            'bui-btn-xs' => $size === 'xs',
        ])
    }}
>
    {{ $slot }}
</span>
