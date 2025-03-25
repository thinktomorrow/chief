@props([
    'size' => 'base',
])

<span
    @class([
        'bui-btn bg-white font-normal text-grey-800 shadow-sm ring-1 ring-inset ring-grey-200 hover:ring-grey-300',
        'bui-btn-base' => $size === 'base',
        'bui-btn-sm' => $size === 'sm',
        'bui-btn-xs' => $size === 'xs',
    ])
>
    <span class="flex items-start gap-1">
        {{ $slot }}
    </span>

    <x-chief::icon.chevron-down class="text-grey-500" />
</span>
