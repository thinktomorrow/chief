@if($field->getValue())
    <div class="prose prose-dark">
        <p>{{ $field->getValue() }}</p>
    </div>
@else
    <p><span class="text-grey-400">...</span></p>
@endif
