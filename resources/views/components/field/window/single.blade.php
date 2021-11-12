<div class="space-y-1">
    @if($field->getLabel())
        <span class="font-medium display-base display-dark">
            {{ ucfirst($field->getLabel()) }}
        </span>
    @endif

    {!! $field->renderOnPage() !!}
</div>
