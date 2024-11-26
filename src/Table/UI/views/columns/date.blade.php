<span class="text-sm leading-5 text-grey-500">
    @foreach ($getItems() as $item)
        {!! $item->getPrependIcon() !!}
        {{ $item->getValue() }}
        {!! $item->getAppendIcon() !!}
    @endforeach
</span>
