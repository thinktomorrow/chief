<x-chief::page>

    <x-slot name="pageTitle">
        @isset($pageTitle)
            {{ $pageTitle }}
        @else
            {{ $resource->getIndexTitle() }}
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
                <h1 class="h1 display-dark">{{ $resource->getIndexTitle() }}</h1>

                @adminCan('create')
                    <a href="@adminRoute('create')" class="btn btn-primary">
                        <x-chief-icon-label type="add">{{ $resource->getLabel() }} toevoegen</x-chief-icon-label>
                    </a>
                @endAdminCan
            </div>
        @endisset
    </x-slot>

    {!! $slot !!}

    <x-slot name="aside">
        @isset($aside)
            {!! $aside !!}
        @else
            @if($resource->getIndexSidebar())
                {!! $resource->getIndexSidebar() !!}
            @endif

            @include('chief::manager._index.filter_card')
            @include('chief::manager._index.sort_card')
            @include('chief::manager._index.archive_card')
        @endisset
    </x-slot>
</x-chief::page>
