@props([
    'autofocus' => false,
])

<input
    type="datetime-local"
    {{ $attributes->merge(['data-slot' => 'control'])->class('form-input-field') }}
    {!! $autofocus ? 'autofocus' : null !!}
/>
