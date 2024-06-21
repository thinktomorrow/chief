@props([
    'size' => 'base',
    'color' => 'grey',
    'iconLeft' => null,
    'iconRight' => null,
])

<span
    {{
        $attributes->class([
            'bui-btn font-medium',
            'bg-grey-100 text-grey-800 hover:bg-grey-200' => $color === 'grey',
            'bg-primary-500 text-white hover:bg-primary-600' => $color === 'primary',
            'bg-white text-grey-800 ring-1 ring-grey-100 hover:ring-grey-200' => $color === 'white',
            'bui-btn-base' => $size === 'base',
            'bui-btn-sm' => $size === 'sm',
            'bui-btn-xs' => $size === 'xs',
        ])
    }}
>
    @if ($iconLeft)
        {!! $iconLeft !!}
    @endif

    @if ($slot->isNotEmpty())
        <span class="bui-btn-content">
            {{ $slot }}
        </span>
    @endif

    @if ($iconRight)
        {!! $iconRight !!}
    @endif
</span>
