@props([
    'autofocus' => false
])

<input
    type="date"
    {{ $attributes->class('form-input-field') }}
    {!! $autofocus ? 'autofocus' : null !!}
/>
