@foreach ($getItems() as $item)
    @if ($item->hasLink())
        <a
            href="{{ $item->getLink() }}"
            title="{{ $item->getValue($getLocale()) }}"
            {!! $item->shouldOpenInNewTab() ? 'target="_blank" rel="noopener"' : '' !!}
            {!! $item->getCustomAttributesAsString() !!}
            class="text-grey-800 leading-5 hover:underline hover:underline-offset-2"
        >
            {!! $item->getValue($getLocale()) !!}
        </a>
    @else
        <span class="text-grey-500 leading-5" {!! $item->getCustomAttributesAsString() !!}>
            {!! $item->getValue($getLocale()) !!}
        </span>
    @endif
@endforeach
