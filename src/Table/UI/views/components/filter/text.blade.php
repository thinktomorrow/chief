@props([
    'size' => 'base',
])

<span
    {{
        $attributes->class([
            'bui-btn bg-white text-grey-500 shadow-sm ring-1 ring-inset ring-grey-100 focus-within:text-grey-800 focus-within:ring-grey-200 hover:ring-grey-200',
            'bui-btn-base' => $size === 'base',
            'bui-btn-sm' => $size === 'sm',
            'bui-btn-xs' => $size === 'xs',
        ])
    }}
>
    <x-chief::icon.search />

    <span class="text-grey-800 *:placeholder:text-grey-500">
        {{ $slot }}
    </span>
</span>
