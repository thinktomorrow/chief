@props([
    'size' => 'base',
    'color' => 'grey',
])

<span
    {{
        $attributes->class([
            'bui-btn cursor-pointer font-medium',
            match ($color) {
                'grey' => 'bui-btn-grey',
                'primary' => 'bui-btn-primary',
                'white' => 'bui-btn-white',
            },
            match ($size) {
                'base' => 'bui-btn-base',
                'sm' => 'bui-btn-sm',
                'xs' => 'bui-btn-xs',
            },
        ])
    }}
>
    {{ $slot }}
</span>
