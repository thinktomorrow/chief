@props([
    'autofocus' => false
])

<textarea
    {{ $attributes->class('form-input-field') }}
    {!! $autofocus ? 'autofocus' : null !!}
>{{ $slot }}</textarea>
