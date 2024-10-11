@props([
    'size' => 'base',
    'variant' => 'tertiary',
])

<span
    {{
        $attributes->class([
            'bui-btn cursor-pointer font-medium',
            match ($variant) {
                'primary' => 'bui-btn-primary',
                'secondary' => 'bui-btn-secondary',
                'tertiary' => 'bui-btn-tertiary',
                'quarternary' => 'bui-btn-quarternary',
                default => 'bui-btn-secondary',
            },
            match ($size) {
                'base' => 'bui-btn-base',
                'sm' => 'bui-btn-sm',
                'xs' => 'bui-btn-xs',
                default => 'bui-btn-base',
            },
        ])
    }}
>
    {{ $slot }}
</span>
