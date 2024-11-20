@props([
    'size' => 'base',
    'variant' => 'grey',
])

<span
    {{
        $attributes->class([
            'badge',
            match ($size) {
                'base' => 'badge-base',
                'sm' => 'badge-sm',
                'xs' => 'badge-xs',
                default => 'badge-base',
            },
            match ($variant) {
                'grey' => 'badge-grey',
                'red' => 'badge-red',
                'orange' => 'badge-orange',
                'green' => 'badge-green',
                default => 'badge-grey',
            },
        ])
    }}
>
    {{ $slot }}
</span>
