<x-chief::page>
    <x-slot name="pageTitle">
        @isset($pageTitle)
            {{ ucfirst($pageTitle) }}
        @else
            {{ ucfirst($resource->getIndexTitle()) }}
        @endisset
    </x-slot>

    <x-slot name="breadcrumbs">
        @isset($breadcrumbs)
            {{ $breadcrumbs }}
        @else
            @if($indexBreadCrumb = $resource->getIndexBreadCrumb())
                <a href="{{ $indexBreadCrumb->url }}" class="link link-primary">
                    <x-chief-icon-label type="back">{{ $indexBreadCrumb->label }}</x-chief-icon-label>
                </a>
            @endif
        @endisset
    </x-slot>

    <x-slot name="header">
        @isset($header)
            {!! $header !!}
        @else
            <div class="flex flex-wrap items-end justify-between gap-6">
                <h1 class="h1 display-dark">{{ ucfirst($resource->getIndexTitle()) }}</h1>

                @if(!isset($is_archive_index) || !$is_archive_index)
                    @adminCan('create')
                        <a href="@adminRoute('create')" class="btn btn-primary-outline">
                            <x-chief-icon-label type="add">{{ $resource->getLabel() }} toevoegen</x-chief-icon-label>
                        </a>
                    @endAdminCan
                @endif
            </div>
        @endisset
    </x-slot>

    {!! $slot !!}

    @if($sidebar)
        <x-slot name="aside">
            @isset($aside)
                {!! $aside !!}
            @else
                @if($resource->getIndexSidebar())
                    {!! $resource->getIndexSidebar() !!}
                @endif

                @include('chief::manager._index.filter_card')

                @adminCan('sort-index', $models->first())
                    @include('chief::manager._index.sort_card')
                @endAdminCan

                @adminCan('archive_index')
                    @include('chief::manager._index.archive_card')
                @endAdminCan
            @endisset
        </x-slot>
    @endif
</x-chief::page>
