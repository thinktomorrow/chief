@php
    $title = ucfirst($resource->getIndexTitle());
@endphp

{{-- TODO: test if this view works correctly --}}
<x-chief::template :title="$title">
    <x-slot name="hero">
        <x-chief::template.hero :title="$title" :breadcrumbs="[$resource->getPageBreadCrumb()]"/>
    </x-slot>

    <x-chief::template.grid>
        <div class="card">
            <div
                data-sortable
                data-sortable-is-sorting
                data-sortable-endpoint="{{ $manager->route('sort-index') }}"
                data-sortable-id-type="{{ $resource->getSortableType() }}"
                class="row-start-stretch gutter-1"
            >
                @foreach($models as $model)
                    <div data-sortable-handle data-sortable-id="{{ $model->getKey() }}" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
                        <div class="h-full p-3 border rounded-md cursor-move transition-75 bg-grey-50 border-grey-100 hover:bg-grey-100">
                            <p class="text-sm display-base display-dark">
                                {{ $resource->getPageTitle($model) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if($models instanceof \Illuminate\Contracts\Pagination\Paginator)
            {!! $models->links('chief::pagination.default') !!}
        @endif

        <x-slot name="aside">
            <div class="space-y-4 card">
                <a href="{{ $manager->route('index') }}" title="Overzicht" class="btn btn-primary">
                    Overzicht
                </a>

                <p class="text-sm body-dark">
                    Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.
                </p>
            </div>
        </x-slot>
    </x-chief::template.grid>
</x-chief::template>
