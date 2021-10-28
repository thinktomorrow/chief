@if($field->getValue())
    <p>{{ optional($field->getValue())->format('Y-m-d') }}</p>
@else
    <p><span class="text-grey-400">...</span></p>
@endif
