@php
    $title = ucfirst($resource->getIndexTitle());
@endphp

<x-chief::page.template :title="$title">
    <x-slot name="hero">
        <x-chief::page.hero :title="$title" :breadcrumbs="[$resource->getIndexBreadCrumb()]">
            @adminCan('create')
                <a
                    href="@adminRoute('create')"
                    title="{{ ucfirst($resource->getLabel()) }} toevoegen"
                    class="btn btn-primary"
                >
                    <x-chief-icon-label type="add">{{ ucfirst($resource->getLabel()) }} toevoegen</x-chief-icon-label>
                </a>
            @endAdminCan
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid>
        <div class="card">
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

        @if ($resource->showIndexSidebarAside())
            <x-slot name="aside">
                @include('chief::templates.page.index.default-sidebar')
            </x-slot>
        @else
            @include('chief::templates.page.index.inline-sidebar')
        @endif
    </x-chief::page.grid>
</x-chief::page.template>
