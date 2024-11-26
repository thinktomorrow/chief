@foreach ($getItems() as $item)
    @if ($item->hasLink())
        <a
            href="{{ $item->getLink() }}"
            title="{{ $item->getValue() }}"
            {!! $item->shouldOpenInNewTab() ? 'target="_blank" rel="noopener"' : '' !!}
            {!! $item->getCustomAttributesAsString() !!}
            class="leading-5 text-grey-800 hover:underline hover:underline-offset-2"
        >
            {!! $item->getValue() !!}
        </a>
    @else
        <span class="leading-5 text-grey-800" {!! $item->getCustomAttributesAsString() !!}>
            {!! $item->getValue() !!}
        </span>
    @endif
@endforeach
