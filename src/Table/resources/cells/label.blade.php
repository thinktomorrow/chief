<span
    {{ $attributes->merge($getCustomAttributes())->merge(['class' => $getLayoutType()->labelClass()]) }}
    title="{{ $getHint() }}"
>
    {!! $getValue() !!}
</span>
