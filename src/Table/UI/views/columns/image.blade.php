@foreach ($getItems() as $item)
    <div class="bg-grey-100 flex size-8 items-center justify-center overflow-hidden rounded-md">
        @if ($item->hasLink())
            <a
                href="{{ $item->getLink() }}"
                title="{{ $item->getValue($getLocale()) }}"
                {!! $item->shouldOpenInNewTab() ? 'target="_blank" rel="noopener"' : '' !!}
                {!! $item->getCustomAttributesAsString() !!}
                class="text-grey-800 leading-5 hover:underline hover:underline-offset-2"
            >
                @if ($item->getValue($getLocale()))
                    <img src="{{ $item->getValue($getLocale()) }}" class="h-full w-full object-cover" />
                @endif
            </a>
        @else
            @if ($item->getValue($getLocale()))
                <img
                    src="{{ $item->getValue($getLocale()) }}"
                    class="h-full w-full object-cover"
                    {!! $item->getCustomAttributesAsString() !!}
                />
            @else
                <x-chief::icon.image class="text-grey-500 size-5" />
            @endif
        @endif
    </div>
@endforeach
