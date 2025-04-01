<h1 class="h1 h1-dark">
    @if (($value = $getValue($locale ?? null)) && is_string($value))
        {{ teaser($value, 120, '...') }}
    @else
        ...
    @endif
</h1>
