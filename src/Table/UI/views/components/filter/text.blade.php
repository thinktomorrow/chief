@props([
    'size' => 'base',
])

<span
    {{
        $attributes->class([
            'bui-btn bg-white text-grey-500 shadow ring-1 ring-grey-200 focus-within:text-grey-800 focus-within:ring-grey-300 hover:ring-grey-300',
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
