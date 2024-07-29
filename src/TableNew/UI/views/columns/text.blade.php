<span class="leading-5 text-grey-800">
    @foreach ($getValues() as $text)
        @if ($text->hasLink())
            <a href="{{ $text->getLink() }}" class="hover:text-primary-500">{{ $text->getValue() }}</a>
        @else
            {{ $text->getValue() }}
        @endif
    @endforeach
</span>
