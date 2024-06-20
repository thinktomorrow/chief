@props([
    'size' => 'base',
    'color' => 'grey',
    'icon' => null,
    'iconPosition' => 'left',
])

<span
    {{
        $attributes->class([
            'bui-btn font-medium shadow-sm ring-1 ring-black/5 hover:shadow hover:ring-black/10',
            'flex-row-reverse' => $iconPosition === 'right',
            'bg-grey-100 text-grey-800' => $color === 'grey',
            'bg-primary-500 text-white' => $color === 'primary',
            'bg-white text-grey-800' => $color === 'white',
            'bui-btn-base' => $size === 'base',
            'bui-btn-sm' => $size === 'sm',
            'bui-btn-xs' => $size === 'xs',
        ])
    }}
>
    @if ($icon)
        {!! $icon !!}
    @endif

    @if ($slot->isNotEmpty())
        <span class="bui-btn-content">
            {{ $slot }}
        </span>
    @endif
</span>
