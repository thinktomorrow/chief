<x-chief::table.data>
    <a
        {{ $attributes->merge($getCustomAttributes()) }}
        href="{{ $getUrl() }}"
        title="{{ $getHint() }}"
    >
        @if (false === strpos($getValue(), '<svg '))
            <x-chief::icon-button :icon="$getValue()" :color="$getColor()" />
        @else
            <x-chief::icon-button :color="$getColor()">
                {!! $getValue() !!}
            </x-chief::icon-button>
        @endif
    </a>
</x-chief::table.data>
