@props([
    'size' => 'base',
    'variant' => 'default',
])

@php
    $attributes = $attributes->class([
        'bui-dropdown-item cursor-pointer font-medium',
        match ($variant) {
            'default' => 'bui-dropdown-item-default',
            //'danger' => 'bui-dropdown-item-danger',
            default => 'bui-dropdown-item-default',
        },
        match ($size) {
            'base' => 'bui-dropdown-item-base',
            //'sm' => 'bui-dropdown-item-sm',
            default => 'bui-dropdown-item-base',
        },
    ]);
@endphp

@if ($attributes->has('href'))
    <a {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button']) }}>
        {{ $slot }}
    </button>
@endif
