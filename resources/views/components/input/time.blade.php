@props([
    'autofocus' => false
])

<input
    type="time"
    {{ $attributes->class('form-input-field') }}
    {!! $autofocus ? 'autofocus' : null !!}
/>
