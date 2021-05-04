@if($field->getValue())
    <p>{{ mb_strimwidth($field->getValue(), 0, 120, '...') }}</p>
@else
    <p><span class="text-grey-400">...</span></p>
@endif
