@props([
    'autofocus' => false,
])

<input
    type="date"
    {{ $attributes->merge(['data-slot' => 'control'])->class('form-input-field') }}
    {!! $autofocus ? 'autofocus' : null !!}
/>
