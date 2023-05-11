<x-chief::table.data>
    <span
        {{ $attributes->merge($getCustomAttributes())->merge(['class' => $getLayoutType()->labelClass()]) }}
        title="{{ $getHint() }}"
    >
        {!! $getValue() !!}
    </span>
</x-chief::table.data>
