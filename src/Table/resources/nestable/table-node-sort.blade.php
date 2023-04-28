<div
    data-sortable-id="{{ $node->getId() }}"
    class="container py-2 nested:ptl sortable-item sorting:nested:p-0 sorting:nested:space-y-4"
>
    <div class="flex items-center justify-between group">
        <div class="flex items-center">
            {{-- Sortable handle icon --}}
            <span data-sortable-handle>
                <x-chief::icon-button icon="icon-chevron-up-down"/>
            </span>

            {{-- Card label --}}
            <a href="{{ $manager->route('edit', $node->getId()) }}" title="{{ $node->getModel()->getPageTitle($node->getModel()) }}">
                <span class="font-medium body h1-dark group-hover:underline">{{ $node->getModel()->getPageTitle($node->getModel()) }}</span>

                @if(\Thinktomorrow\Chief\Admin\Settings\Homepage::is($node->getModel()))
                    <span class="inline mr-1 label label-primary">Homepage</span>
                @endif

                @if($node->getModel() instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract && !$node->getModel()->inOnlineState())
                    <span class="inline mr-1 label label-error">Offline</span>
                @endif
            </a>
        </div>
    </div>

    <div
        data-sortable
        data-sortable-group-id="{{ $node->getId() }}"
        data-sortable-endpoint="{{ $manager->route('sort-index') }}"
        data-sortable-nested-endpoint="{{ $manager->route('move-index') }}"
        class="relative w-full has-nested-items sorting:drop-zone"
    >
        @foreach($node->getChildNodes() as $child)
            @include('chief-table::nestable.table-node-sort', ['node' => $child])
        @endforeach
    </div>
</div>
