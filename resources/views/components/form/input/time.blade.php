@props([
    'autofocus' => false,
])

<input
    type="time"
    {{ $attributes->merge(['data-slot' => 'control'])->class('form-input-field') }}
    {!! $autofocus ? 'autofocus' : null !!}
/>
