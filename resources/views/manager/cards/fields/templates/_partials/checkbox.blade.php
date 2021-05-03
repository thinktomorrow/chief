@if($field->getValue())
    @if(gettype($field->getValue()) == 'array')
        <p>{{ implode(', ', $field->getValue()) }}</p>
    @else
        <p>{{ $field->getValue() }}</p>
    @endif
@else
    <p><span class="text-grey-400">...</span></p>
@endif
