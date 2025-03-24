@props([
    'autofocus' => false,
])

<input
    type="text"
    {{ $attributes->merge(['data-slot' => 'control'])->class('form-input-field') }}
    {!! $autofocus ? 'autofocus' : null !!}
/>
