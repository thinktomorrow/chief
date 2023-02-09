@props([
    'checked' => false
])

<input
    type="radio"
    {!! $checked ? 'checked' : null !!}
    {{ $attributes->class('form-input-radio') }}
>
