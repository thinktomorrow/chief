<x-chief::table.data>
    <a
        {{ $attributes->merge($getCustomAttributes()) }}
        href="{{ $getUrl() }}"
        title="{{ $getDescription() }}"
    >
        <x-chief-icon-button icon="{{ $getKey() }}"></x-chief-icon-button>
    </a>
</x-chief::table.data>
