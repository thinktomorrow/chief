<span class="text-sm leading-5 text-grey-500">
    @foreach ($getItems() as $item)
        {{ $item->getValue($getLocale()) }}
    @endforeach
</span>
