<span
    {{ $attributes->merge($getCustomAttributes()) }}
    title="{{ $getHint() }}"
>
    {!! $getValue() !!}
</span>
