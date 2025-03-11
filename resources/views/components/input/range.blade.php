@props([
    'autofocus' => false,
])

<input
    type="range"
    {{ $attributes->merge(['data-slot' => 'control'])->class('form-input-field') }}
    {!! $autofocus ? 'autofocus' : null !!}
/>
