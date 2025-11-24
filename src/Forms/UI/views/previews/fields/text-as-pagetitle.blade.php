<h1 class="h1 h1-dark wrap-anywhere">
    @if (($value = $getValueOrFallback($locale ?? null)) && is_string($value))
        {{ teaser($value, 120, '...') }}
    @else
        ...
    @endif
</h1>
