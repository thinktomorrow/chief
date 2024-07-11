@foreach($getValues() as $text)
    @if($text->hasLink())
        <a href="{{ $text->getLink() }}">{{ $text->getValue() }}</a>
    @else
        {{ $text->getValue() }}
    @endif
@endforeach
