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
            'quaternary' => 'bui-btn-quaternary',
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
@elseif ($attributes->has('for'))
    <label {{ $attributes }}>
        {{ $slot }}
    </label>
@else
    <button {{ $attributes->merge(['type' => 'button']) }}>
        {{ $slot }}
    </button>
@endif
