<p class="body-base body-dark">
    @if($value = $getValue($locale ?? null))
        {{ teaser($value, 120, '...') }}
    @else
        ...
    @endif
</p>
