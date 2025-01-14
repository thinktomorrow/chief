@props([
    'size' => 'base',
    'variant' => 'grey',
])

@php
    $attributes = $attributes->class([
        'bui-dropdown-item cursor-pointer font-medium',
        match ($size) {
            'base' => 'bui-dropdown-item-base',
            default => 'bui-dropdown-item-base',
        },
        match ($variant) {
            'grey' => 'bui-dropdown-item-grey',
            'red' => 'bui-dropdown-item-red',
            'orange' => 'bui-dropdown-item-orange',
            'light-blue' => 'bui-dropdown-item-light-blue',
            'green' => 'bui-dropdown-item-green',
            default => 'bui-dropdown-item-grey',
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
