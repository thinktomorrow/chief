<div class="space-y-6 card">
    <div class="w-full space-x-1">
        <span class="text-lg display-base display-dark">
            Sortering
        </span>
    </div>

    <div class="space-y-4">
        @if(!$models instanceof Illuminate\Contracts\Pagination\Paginator || !$models->hasPages())
            <p class="body-dark body-base">
                Deze pagina's worden op de site weergegeven volgens een handmatige sortering.
            </p>

            <button data-sortable-toggle class="btn btn-primary">
                Pas volgorde aan
            </button>

            <p class="text-sm body-dark body-base" data-sortable-show-when-sorting>
                Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.
            </p>
        @else
            <p class="body-dark body-base">
                Deze pagina's worden op de site weergegeven volgens een handmatige sortering.
            </p>

            <a href="{{ $manager->route('index-for-sorting') }}" class="btn btn-primary">Sorteer handmatig</a>
        @endif
    </div>
</div>
