@foreach ($getItems() as $item)
    <div class="flex size-8 items-center justify-center overflow-hidden rounded-md bg-grey-100">

        @if ($item->hasLink())
            <a
                href="{{ $item->getLink() }}"
                title="{{ $item->getValue() }}"
                {!! $item->shouldOpenInNewTab() ? 'target="_blank" rel="noopener"' : '' !!}
                {!! $item->getCustomAttributesAsString() !!}
                class="leading-5 text-grey-800 hover:underline hover:underline-offset-2"
            >
                @if ($item->getValue())
                    <img src="{{ $item->getValue() }}" class="h-full w-full object-cover" />
                @endif
            </a>
        @else
            @if ($item->getValue())
                <img src="{{ $item->getValue() }}"
                     class="h-full w-full object-cover" {!! $item->getCustomAttributesAsString() !!} />
            @else
                <x-chief::icon.image class="size-5 text-grey-500" />
            @endif
        @endif
    </div>

@endforeach
