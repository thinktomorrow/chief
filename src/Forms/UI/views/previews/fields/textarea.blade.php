<p class="body body-dark">
    @if($value = $getValueOrFallback($locale ?? null))
        {{ teaser($value, 120, '...') }}
    @else
        ...
    @endif
</p>
