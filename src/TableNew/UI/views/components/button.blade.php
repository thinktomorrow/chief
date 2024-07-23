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
            'bui-btn-grey' => $color === 'grey',
            'bui-btn-primary' => $color === 'primary',
            'bui-btn-white' => $color === 'white',
            'bui-btn-base' => $size === 'base',
            'bui-btn-sm' => $size === 'sm',
            'bui-btn-xs' => $size === 'xs',
        ])
    }}
>
    @if ($iconLeft)
        {!! $iconLeft !!}
    @endif

    @if ($slot->isNotEmpty() && $slot->hasActualContent())
        <span class="bui-btn-content">
            {{ $slot }}
        </span>
    @endif

    @if ($iconRight)
        {!! $iconRight !!}
    @endif
</span>
