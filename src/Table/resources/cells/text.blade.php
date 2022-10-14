<x-chief::table.data>
    <span
        {{ $attributes->merge($getCustomAttributes()) }}
        title="{{ $getHint() }}"
    >
        {!! $getValue() !!}
    </span>
</x-chief::table.data>
