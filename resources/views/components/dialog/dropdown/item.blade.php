@props([
    'size' => 'base',
    'variant' => 'grey',
])

@php
    $attributes = $attributes->class([
        'dropdown-item cursor-pointer font-medium',
        match ($size) {
            'base' => 'dropdown-item-base',
            default => 'dropdown-item-base',
        },
        match ($variant) {
            'grey' => 'dropdown-item-grey',
            'red' => 'dropdown-item-red',
            'orange' => 'dropdown-item-orange',
            'light-blue' => 'dropdown-item-light-blue',
            'green' => 'dropdown-item-green',
            default => 'dropdown-item-grey',
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
