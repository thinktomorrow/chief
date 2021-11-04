<div class="space-y-1">
    @if($field->getLabel())
        <span class="font-medium text-black">
            {{ ucfirst($field->getLabel()) }}
        </span>
    @endif

    {!! $field->renderOnPage() !!}
</div>
