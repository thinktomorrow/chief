<x-chief::table.data>
    <a
        {{ $attributes->merge($getCustomAttributes()) }}
        href="{{ $getUrl() }}"
        title="{{ $getHint() }}"
        class="font-medium body-dark hover:underline"
    >
        {{ $getValue() }}
    </a>
</x-chief::table.data>
