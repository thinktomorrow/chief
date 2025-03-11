@props([
    'autofocus' => false,
])

<textarea
    {{ $attributes->merge(['data-slot' => 'control'])->class('form-input-field') }}
    {!! $autofocus ? 'autofocus' : null !!}
>
{{ $slot }}</textarea
>
