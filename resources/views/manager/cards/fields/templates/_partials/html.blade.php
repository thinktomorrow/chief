{{ dd($field->getValue())}}
@if($field->getValue())
    {{-- TODO: We need a sensible way to truncate HTML, as teaser doesn't work without HTMLPurifier --}}
    {!! $field->getValue() !!}
@else
    <p><span class="text-grey-400">...</span></p>
@endif
