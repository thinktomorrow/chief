@props([
    'size' => 'base',
    'variant' => 'outline-white',
])

@php
    $attributes = $attributes->class([
        'bui-btn cursor-pointer font-medium',
        match ($size) {
            'base' => 'bui-btn-base',
            'sm' => 'bui-btn-sm',
            'xs' => 'bui-btn-xs',
            default => 'bui-btn-base',
        },
        match ($variant) {
            'blue' => 'bui-btn-blue',
            'grey' => 'bui-btn-grey',
            'outline-white' => 'bui-btn-outline-white',
            'transparent' => 'bui-btn-transparent',
            'red' => 'bui-btn-red',
            'orange' => 'bui-btn-orange',
            'light-blue' => 'bui-btn-light-blue',
            'green' => 'bui-btn-green',
            default => 'bui-btn-outline-white',
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
