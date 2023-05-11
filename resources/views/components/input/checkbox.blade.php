@props([
    'checked' => false
])

<input
    type="checkbox"
    {!! $checked ? 'checked' : null !!}
    {{ $attributes->class('form-input-checkbox') }}
>
