@props([
    'autofocus' => false
])

<input
    type="text"
    {{ $attributes->class('form-input-field') }}
    {!! $autofocus ? 'autofocus' : null !!}
/>
