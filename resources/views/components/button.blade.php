@props([
    'size' => 'base',
    'variant' => 'outline-white',
])

@php
    $attributes = $attributes->class([
        'bui-btn cursor-pointer',
        match ($size) {
            'lg' => 'bui-btn-lg',
            'base' => 'bui-btn-base',
            'sm' => 'bui-btn-sm',
            'xs' => 'bui-btn-xs',
            default => 'bui-btn-base',
        },
        match ($variant) {
            'blue' => 'bui-btn-blue',
            'grey' => 'bui-btn-grey',
            'transparent' => 'bui-btn-transparent',
            'red' => 'bui-btn-red',
            'orange' => 'bui-btn-orange',
            'green' => 'bui-btn-green',
            'outline-white' => 'bui-btn-outline-white',
            'outline-blue' => 'bui-btn-outline-blue',
            'outline-green' => 'bui-btn-outline-green',
            'outline-red' => 'bui-btn-outline-red',
            'outline-orange' => 'bui-btn-outline-orange',
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
