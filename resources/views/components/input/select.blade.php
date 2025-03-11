@props([
    'multiple' => false,
])

<div data-slot="control" class="relative flex items-center justify-end">
    <select
        {!! $multiple ? 'multiple' : null !!}
        {{ $attributes->merge(['data-slot' => 'control'])->class('form-input-field appearance-none !pr-9') }}
    >
        {{ $slot }}
    </select>

    <x-chief::icon.arrow-down class="body-dark pointer-events-none absolute right-3 size-4" />
</div>
