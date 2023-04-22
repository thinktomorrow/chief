@php
    $title = ucfirst($resource->getIndexTitle());
@endphp

<x-chief::page.template :title="$title">
    <x-slot name="hero">
        <x-chief::page.hero :title="$title" :breadcrumbs="[$resource->getIndexBreadCrumb()]">
            @if($resource->getIndexHeaderContent())
                {!! $resource->getIndexHeaderContent() !!}
            @endif
            @adminCan('create')
                {{-- <a
                    href="@adminRoute('create')"
                    title="{{ ucfirst($resource->getLabel()) }} toevoegen"
                    class="btn btn-primary"
                >
                    <x-chief::icon-label type="add">{{ ucfirst($resource->getLabel()) }} toevoegen</x-chief::icon-label>
                </a> --}}
                <a
                    href="@adminRoute('create')"
                    title="{{ ucfirst($resource->getLabel()) }} toevoegen"
                    class="flex items-center justify-center w-8 h-8 rounded-full text-grey-600 bg-grey-100"
                >
                    <x-chief::icon-button icon="icon-plus" color="grey"/>
                </a>
            @endAdminCan
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid>
        <div class="p-6">
            @if (!$tree->isEmpty())
                <div
                    data-sortable
                    data-sortable-group-id="{{ $resource::resourceKey() }}"
                    data-sortable-endpoint="{{ $manager->route('sort-index') }}"
                    data-sortable-nested-endpoint="{{ $manager->route('move-index') }}"
                    data-sortable-id-type="{{ $resource->getSortableType() }}"
                    data-sortable-class-when-sorting="is-sorting"
                    class="-my-3 divide-y divide-grey-100"
                >
                    @foreach($tree as $node)
                        @include('chief-table::nestable.node', ['node' => $node, 'level' => 0])
                    @endforeach
                </div>
            @else
                <p class="body-dark">
                    Nog geen items toegevoegd.
                    <a
                        href="@adminRoute('create')"
                        title="Voeg een eerste item toe"
                        class="link link-primary"
                    >Voeg een eerste item toe</a>.
                </p>
            @endif
        </div>

        {{-- @if ($resource->showIndexSidebarAside())
            <x-slot name="aside">
                @include('chief::templates.page.index.default-sidebar')
            </x-slot>
        @else
            @include('chief::templates.page.index.inline-sidebar')
        @endif --}}
    </x-chief::page.grid>

    @if ($resource->showIndexSidebarAside())
        <x-slot name="sidebar">
            @include('chief::templates.page.index.default-sidebar')
        </x-slot>
    @else
        @include('chief::templates.page.index.inline-sidebar')
    @endif
</x-chief::page.template>
