@if($field->getValue())
    <div class="prose-editor line-clamp-6">
        {!! $field->getValue() !!}
    </div>
@else
    <p><span class="text-grey-400">...</span></p>
@endif
