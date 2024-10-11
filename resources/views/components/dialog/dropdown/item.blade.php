@props([
    'size' => 'base',
    'variant' => 'default',
])

<span
    {{
        $attributes->class([
            'bui-dropdown-item cursor-pointer font-medium',
            match ($variant) {
                'default' => 'bui-dropdown-item-default',
                {{-- 'danger' => 'bui-dropdown-item-danger', --}}
                default => 'bui-dropdown-item-default',
            },
            match ($size) {
                'base' => 'bui-dropdown-item-base',
                {{-- 'sm' => 'bui-dropdown-item-sm', --}}
                default => 'bui-dropdown-item-base',
            },
        ])
    }}
>
    {{ $slot }}
</span>
