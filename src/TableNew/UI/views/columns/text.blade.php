@foreach($getValues() as $text)
    @if($text->hasLink())
        <a href="{{ $text->getLink() }}">{{ $text->getValue() }}</a>
    @endif

    {{ $text->getValue() }}
@endforeach
