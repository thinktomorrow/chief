<a
    {{ $attributes->merge($getCustomAttributes()) }}
    href="{{ $getUrl() }}"
    title="{{ $getHint() }}"
    class="font-medium body-dark"
>
    {!! $getValue() !!}
</a>
