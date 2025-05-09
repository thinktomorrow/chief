@props([
    'checked' => false,
])

<input
    type="checkbox"
    {!! $checked ? 'checked' : null !!}
    {{ $attributes->merge(['data-slot' => 'control'])->class('form-input-checkbox') }}
/>
