<div class="prose prose-dark prose-editor">
    @if($field->getValue())
        <p>{{ teaser($field->getValue(), 120, '...') }}</p>
    @else
        <p>...</p>
    @endif
</div>
