@props([
    'size' => 'base',
    'variant' => 'body-dark',
])

@php
    $attributes = $attributes->class([
        'bui-link cursor-pointer',
        match ($size) {
            'base' => 'bui-link-base',
            'sm' => 'bui-link-sm',
            'xs' => 'bui-link-xs',
            default => 'bui-link-base',
        },
        match ($variant) {
            'blue' => 'bui-link-blue',
            'body-dark' => 'bui-link-body-dark',
            default => 'bui-link-body-dark',
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
