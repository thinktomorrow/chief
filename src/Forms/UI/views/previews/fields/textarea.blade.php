<p class="body body-dark wrap-anywhere">
    @if ($value = $getValueOrFallback($locale ?? null))
        {{ teaser($value, 120, '...') }}
    @else
        ...
    @endif
</p>
