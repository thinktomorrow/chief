@if($field->getValue())
    {{ $field->getValue() }}
@else
    <p><span class="text-grey-400">...</span></p>
@endif
