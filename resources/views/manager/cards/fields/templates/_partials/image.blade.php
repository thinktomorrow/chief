@if(count($field->getValue()) > 0)
    @foreach ($field->getValue() as $image)
        {{-- TODO: use thumb conversion --}}
        <img src="{{ $image->url }}" title="{{ $image->filename }}">
    @endforeach
@else
    <p class="text-grey-400">...</p>
@endif
