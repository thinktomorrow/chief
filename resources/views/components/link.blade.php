@props([
    'size' => 'base',
    'variant' => 'body-dark',
])

@php
    $attributes = $attributes->class([
        'link cursor-pointer',
        match ($size) {
            'base' => 'link-base',
            'sm' => 'link-sm',
            'xs' => 'link-xs',
            default => 'link-base',
        },
        match ($variant) {
            'blue' => 'link-blue',
            'red' => 'link-red',
            'body-dark' => 'link-body-dark',
            default => 'link-body-dark',
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
