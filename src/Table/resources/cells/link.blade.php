<x-chief::table.data>
    <a
        {{ $attributes->merge($getCustomAttributes()) }}
        href="{{ $getUrl() }}"
        title="{{ $getDescription() }}"
        class="font-medium body-dark hover:underline"
    >
        {{ $getTitle() }}
    </a>
</x-chief::table.data>
