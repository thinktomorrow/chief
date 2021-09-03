@if($field->getValue())
    <p>{{ teaser($field->getValue(), 40, '...') }}</p>
@else
    <p><span class="text-grey-400">...</span></p>
@endif


