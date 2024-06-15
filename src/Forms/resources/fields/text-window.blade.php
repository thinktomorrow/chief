<p class="body body-dark">
    @if(($value = $getValue($locale ?? null)) && is_string($value))
        {{ teaser($value, 120, '...') }}
    @else
        ...
    @endif
</p>
