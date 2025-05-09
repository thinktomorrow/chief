@props([
    'checked' => false,
])

<input
    type="radio"
    {!! $checked ? 'checked' : null !!}
    {{ $attributes->merge(['data-slot' => 'control'])->class('form-input-radio') }}
/>
