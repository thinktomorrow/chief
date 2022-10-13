<x-chief::table.data>
    <a
        {{ $attributes->merge($getCustomAttributes()) }}
        href="{{ $getUrl() }}"
        title="{{ $getDescription() }}"
    >
        @if (false === strpos($getKey(), '<svg '))
            <x-chief-icon-button :icon="$getKey()" :color="$getColor()" />
        @else
            <x-chief-icon-button :color="$getColor()">
                {!! $getKey() !!}
            </x-chief-icon-button>
        @endif
    </a>
</x-chief::table.data>
