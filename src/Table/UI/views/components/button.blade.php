@props([
    'size' => 'base',
    'variant' => 'tertiary',
])

@php
    $attributes = $attributes->class([
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
