@props([
    'size' => 'base',
    'variant' => 'outline-white',
])

@php
    $attributes = $attributes->class([
        'btn cursor-pointer',
        match ($size) {
            'lg' => 'btn-lg',
            'base' => 'btn-base',
            'sm' => 'btn-sm',
            'xs' => 'btn-xs',
            default => 'btn-base',
        },
        match ($variant) {
            'blue' => 'btn-blue',
            'grey' => 'btn-grey',
            'transparent' => 'btn-transparent',
            'red' => 'btn-red',
            'orange' => 'btn-orange',
            'green' => 'btn-green',
            'outline-white' => 'btn-outline-white',
            'outline-blue' => 'btn-outline-blue',
            'outline-green' => 'btn-outline-green',
            'outline-red' => 'btn-outline-red',
            'outline-orange' => 'btn-outline-orange',
            default => 'btn-outline-white',
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
