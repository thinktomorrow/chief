<span class="text-sm leading-5 text-grey-500">
    @foreach ($getValues() as $text)
        {{ $text->getValue() }}
    @endforeach
</span>
