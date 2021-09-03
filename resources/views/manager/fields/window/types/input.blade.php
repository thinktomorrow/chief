<div class="prose prose-editor prose-dark">
    @if($field->getValue())
        <p>{{ teaser($field->getValue(), 120, '...') }}</p>
    @else
        <p>...</p>
    @endif
</div>
