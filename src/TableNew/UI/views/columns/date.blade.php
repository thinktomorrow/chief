@foreach ($getValues() as $text)
    <span class="text-sm text-grey-500">{{ $text->getValue() }}</span>
@endforeach
