@php
    use Thinktomorrow\Chief\TableNew\Table\TableReference;

    $is_archive_index = $is_archive_index ?? false;
    $title = ucfirst($resource->getIndexTitle());

    if($is_archive_index) {
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

    <div class="container space-y-4">
        {{ $table->render() }}
{{--        {{ $table2->render() }}--}}

        <div>
            <div class="row-start-start gutter-3">
                @if ($resource->getIndexSidebar())
                    <div class="w-full md:w-1/2 2xl:w-1/3">
                        {!! $resource->getIndexSidebar() !!}
                    </div>
                @endif

                {{--            @adminCan('sort-index', $models->first())--}}
                {{--                <div class="w-full md:w-1/2 2xl:w-1/3">--}}
                {{--                    @include('chief::manager._index.sort_card')--}}
                {{--                </div>--}}
                {{--            @endAdminCan--}}

                @adminCan('archive_index')
                    <div class="w-full md:w-1/2 2xl:w-1/3">
                        @if($is_archive_index)
                            <a href="@adminRoute('index')" class="inline-block" title="Terug naar het overzicht">
                                <x-chief-table-new::button color="white">Terug naar het overzicht</x-chief-table-new::button>
                            </a>
                        @else
                            <a href="@adminRoute('archive_index')" class="inline-block" title="Bekijk archief">
                                <x-chief-table-new::button color="white">Bekijk archief</x-chief-table-new::button>
                            </a>
                        @endif
                    </div>
                @endAdminCan
            </div>
        </div>
    </div>

</x-chief::page.template>
