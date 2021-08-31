@if($field->getValue())
    <div class="prose-editor" style="
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 6;
    ">
        {!! $field->getValue() !!}
    </div>
@else
    <p><span class="text-grey-400">...</span></p>
@endif
