<div class="overflow-hidden max-h-20">
    @if($getValue($locale ?? null))
        <p class="text-grey-500">{{ teaser($getValue($locale ?? null), 120, '...') }}</p>
    @else
        <p class="text-grey-500">...</p>
    @endif
</div>

