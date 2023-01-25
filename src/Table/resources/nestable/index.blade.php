@php
    $title = $root ? $root->getLabel() : ucfirst($resource->getIndexTitle());
@endphp

<x-chief::template :title="$title">
    <x-slot name="hero">
        <x-chief::template.hero :title="$title">
            @adminCan('create')
                <a
                    href="@adminRoute('create'){{ $root ? '?parent_id=' . $root->getId() : null }}"
                    title="{{ ucfirst($resource->getLabel()) }} toevoegen"
                    class="btn btn-primary"
                >
                    <x-chief-icon-label type="add">{{ ucfirst($resource->getLabel()) }} toevoegen</x-chief-icon-label>
                </a>
            @endAdminCan
        </x-chief::template.hero>
    </x-slot>

    <div class="container">
        @if (!$tree->isEmpty())
            <div class="card">
                <div
                    data-sortable
                    data-sortable-group-id="{{ $root?->getId() }}"
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
            </div>
        @else
            <div class="card">
                <p class="body-dark">
                    Nog geen items toegevoegd.
                    <a
                        href="@adminRoute('create'){{ $root ? '?parent_id=' . $root->getId() : null }}"
                        title="Voeg een eerste item toe"
                        class="link link-primary"
                    >Voeg een eerste item toe</a>.
                </p>
            </div>
        @endif
    </div>
</x-chief:template>
