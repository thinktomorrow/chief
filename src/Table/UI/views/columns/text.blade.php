@foreach ($getItems() as $item)
    <span
        class="inline-flex items-start gap-1 [&>[data-slot=icon]]:size-5 [&>[data-slot=icon]]:shrink-0 [&>svg]:size-5 [&>svg]:shrink-0"
    >
        {!! $item->getPrependIcon() !!}

        @if ($item->hasLink())
            <a
                href="{{ $item->getLink() }}"
                title="{{ $item->getValue() }}"
                {!! $item->shouldOpenInNewTab() ? 'target="_blank" rel="noopener"' : '' !!}
                {!! $item->getCustomAttributesAsString() !!}
                class="leading-5 text-grey-800 hover:underline hover:underline-offset-2"
            >
                {{ $item->getValue() }}
            </a>
        @else
            <span class="leading-5 text-grey-800" {!! $item->getCustomAttributesAsString() !!}>
                {{ $item->getValue() }}
            </span>
        @endif

        {!! $item->getAppendIcon() !!}
    </span>
@endforeach
