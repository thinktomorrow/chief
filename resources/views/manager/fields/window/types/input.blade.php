<div class="overflow-hidden max-h-20">
    @if($field->getValue())
        <p class="text-grey-500">{{ teaser($field->getValue(), 120, '...') }}</p>
    @else
        <p class="text-grey-500">...</p>
    @endif
</div>
