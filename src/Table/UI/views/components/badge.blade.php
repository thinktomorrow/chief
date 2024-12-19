@props([
    'size' => 'xs',
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
                default => 'badge-xs',
            },
            match ($variant) {
                'grey' => 'badge-grey',
                'red' => 'badge-red',
                'orange' => 'badge-orange',
                'green' => 'badge-green',
                'outline-transparent' => 'badge-outline-transparent',
                default => 'badge-grey',
            },
        ])
    }}
>
    {{ $slot }}
</span>
