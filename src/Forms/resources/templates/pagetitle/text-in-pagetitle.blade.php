@if($getValue($locale ?? null))
    {{ teaser($getValue($locale ?? null), 120, '...') }}
@else
    ...
@endif
