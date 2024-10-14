@props([
    'size' => 'base',
])

<span
    @class([
        'bui-btn bg-white text-grey-800 shadow-sm ring-1 ring-inset ring-grey-100 hover:ring-grey-200',
        'bui-btn-base' => $size === 'base',
        'bui-btn-sm' => $size === 'sm',
        'bui-btn-xs' => $size === 'xs',
    ])
>
    <span class="flex items-start gap-1">
        {{ $slot }}
    </span>

    <x-chief::icon.arrow-down class="text-grey-500" />
</span>
