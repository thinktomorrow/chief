@props([
    'autofocus' => false
])

<input
    type="range"
    {{ $attributes->class('form-input-field') }}
    {!! $autofocus ? 'autofocus' : null !!}
/>
