<p class="body body-dark">
    @if(!is_null(($value = $getValueOrFallback($locale ?? null))))
        {{ is_string($value) ? teaser($value, 120, '...') : $value }}
    @else
        ...
    @endif
</p>
