@props([
    'size' => 'base',
])

<span
    @class([
        'bui-btn bg-white text-grey-800 shadow ring-1 ring-grey-200 hover:ring-grey-300',
        'bui-btn-base' => $size === 'base',
        'bui-btn-sm' => $size === 'sm',
        'bui-btn-xs' => $size === 'xs',
    ])
>
    {{ $slot }}
</span>
