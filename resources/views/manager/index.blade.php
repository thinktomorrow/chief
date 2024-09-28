@php
    use Thinktomorrow\Chief\Table\Table\References\TableReference;

    $is_archive_index = $is_archive_index ?? false;
    $title = ucfirst($resource->getIndexTitle());

    if ($is_archive_index) {
        $table = $resource->getArchivedIndexTable();
        $table->setTableReference(new TableReference($resource::class, 'getArchivedIndexTable'));
    } else {
        $table = $resource->getIndexTable();
        $table->setTableReference(new TableReference($resource::class, 'getIndexTable'));

        $table2 = $resource->getOtherIndexTable();
        $table2->setTableReference(new TableReference($resource::class, 'getOtherIndexTable'));
    }
@endphp

<x-chief::page.template>
    <x-slot name="hero">
        <x-chief::page.hero :title="$title" :breadcrumbs="$is_archive_index ? [$resource->getPageBreadCrumb()] : []">
            @if ($resource->getIndexDescription())
                <x-slot name="description">
                    {{ $resource->getIndexDescription() }}
                </x-slot>
            @endif

            @if ($resource->getIndexHeaderContent())
                {!! $resource->getIndexHeaderContent() !!}
            @endif
        </x-chief::page.hero>
    </x-slot>

    <div class="container">
        {{ $table->render() }}

        @adminCan('archive_index')
        <div>
            @if ($is_archive_index)
                <a href="@adminRoute('index')" class="inline-block" title="Terug naar het overzicht">
                    <x-chief-table::button color="white">Terug naar het overzicht</x-chief-table::button>
                </a>
            @else
                <a href="@adminRoute('archive_index')" class="inline-block" title="Bekijk archief">
                    <x-chief-table::button color="white">Bekijk archief</x-chief-table::button>
                </a>
            @endif
        </div>
        @endAdminCan
    </div>
</x-chief::page.template>
