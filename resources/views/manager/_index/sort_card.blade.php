@php
    $possibleToSortInline = $model instanceof \Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable || (!$models instanceof Illuminate\Contracts\Pagination\Paginator || !$models->hasPages());
@endphp

<x-chief-window title="Sortering" class="card">
    <div class="space-y-4">
        @if($resource->allowInlineSorting() && $possibleToSortInline)
            <p class="body-dark body">
                Deze pagina's worden op de site weergegeven volgens een handmatige sortering.
            </p>

            <button data-sortable-toggle class="btn btn-primary">
                Pas volgorde aan
            </button>

            <p class="text-sm body-dark body" data-sortable-show-when-sorting>
                Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.
            </p>
        @else
            <p class="body-dark body">
                Deze pagina's worden op de site weergegeven volgens een handmatige sortering.
            </p>

            <a href="{{ $manager->route('index-for-sorting') }}" class="btn btn-primary">Sorteer handmatig</a>
        @endif
    </div>
</x-chief-window>
