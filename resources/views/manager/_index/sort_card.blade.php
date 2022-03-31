@adminCan('sort-index', $models->first())
    <div class="card">
        <div class="w-full space-x-1 mt-0.5">
            <span class="text-lg display-base display-dark">
                Sortering
            </span>
        </div>

        @if(!$models instanceof Illuminate\Contracts\Pagination\Paginator || !$models->hasPages())
            <p class="text-grey-700">
                Deze pagina's worden op de site weergegeven volgens een handmatige sortering.
            </p>

            <button data-sortable-toggle class="btn btn-primary mt-4 mb-4">
                Pas volgorde aan
            </button>

            <p class="text-grey-700 font-xs" data-sortable-show-when-sorting>
                Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.
            </p>
        @else
            <p class="text-sm text-grey-700">
                Deze pagina's worden op de site weergegeven volgens een handmatige sortering.
            </p>

            <a href="{{ $manager->route('index-for-sorting') }}" class="btn btn-primary mt-3">Sorteer handmatig</a>
        @endif
    </div>
@endAdminCan
