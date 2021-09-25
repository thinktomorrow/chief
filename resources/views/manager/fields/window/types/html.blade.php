<div
    class="prose prose-dark prose-editor"
    style="
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    "
>
    @if($field->getValue())
        {!! $field->getValue() !!}
    @else
        <p><span class="text-grey-400">...</span></p>
    @endif
</div>
