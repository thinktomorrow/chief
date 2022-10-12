<x-chief::table.data>
    <span
        {{ $attributes->merge($getCustomAttributes()) }}
        title="{{ $getDescription() }}"
    >
        {!! $getTitle() !!}
    </span>
</x-chief::table.data>
