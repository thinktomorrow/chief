@props([
    'autofocus' => false
])

<input
    type="number"
    {{ $attributes->class('form-input-field') }}
    {!! $autofocus ? 'autofocus' : null !!}
/>
