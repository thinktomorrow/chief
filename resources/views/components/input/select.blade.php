@props([
    'multiple' => false,
])

<div class="relative flex items-center justify-end">
    <select
        {!! $multiple ? 'multiple' : null !!}
        {{ $attributes->class('form-input-field appearance-none !pr-9') }}
    >
        {{ $slot }}
    </select>

    <svg class="absolute w-4 h-4 pointer-events-none right-3 body-dark">
        <use xlink:href="#icon-chevron-down"></use>
    </svg>
</div>
