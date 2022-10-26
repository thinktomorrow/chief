<x-chief::index :sidebar="true">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <h1 class="h1 display-dark">{{ $root ? $root->getLabel() : ucfirst($resource->getIndexTitle()) }}</h1>
            @adminCan('create')
                <a href="@adminRoute('create'){{ $root ? '?parent_id=' . $root->getId() : null }}" class="btn btn-primary-outline">
                    <x-chief-icon-label type="add">{{ $resource->getLabel() }} toevoegen</x-chief-icon-label>
                </a>
            @endAdminCan
        </div>
    </x-slot>

    @if(!$tree->isEmpty())
        <div class="card">
            <div
                data-sortable
                data-sortable-group-id="{{ $root?->getId() }}"
                data-sortable-endpoint="{{ $manager->route('sort-index') }}"
                data-sortable-nested-endpoint="{{ $manager->route('move-index') }}"
                data-sortable-id-type="{{ $resource->getSortableType() }}"
                data-sortable-class-when-sorting="is-sorting"
                class="-my-4 divide-y divide-grey-100"
                style="padding: 0;"
            >
                @foreach($tree as $node)
                    @include('chief-table::nestable.node', [
                        'node' => $node,
                        'level' => 0,
                    ])
                @endforeach
            </div>
        </div>
    @else
        <div class="card">Nog geen items toegevoegd.
            <a href="@adminRoute('create'){{ $root ? '?parent_id=' . $root->getId() : null }}" class="link link-primary">
                Voeg een eerste item toe
            </a>
        </div>
    @endif
</x-chief::index>
