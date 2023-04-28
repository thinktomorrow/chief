<div data-sortable-id="{{ $node->getId() }}" @class([
    'group-[.is-nested]:p-0',
    'px-8 py-3.5' => $level == 0,
])>
    <div class="flex items-center justify-between -my-2">
        <div class="flex items-center gap-1">
            {{-- Sortable handle icon --}}
            <button data-sortable-handle type="button" class="-ml-2">
                <x-chief::icon-button icon="icon-chevron-up-down"/>
            </button>

            {{-- Card label --}}
            <div>
                <span class="font-medium body body-dark">
                    {{ $node->getModel()->getPageTitle($node->getModel()) }}
                </span>

                @if(\Thinktomorrow\Chief\Admin\Settings\Homepage::is($node->getModel()))
                    <span class="inline mr-1 label label-primary">Homepage</span>
                @endif

                @if($node->getModel() instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract && !$node->getModel()->inOnlineState())
                    <span class="inline mr-1 label label-error">Offline</span>
                @endif
            </div>
        </div>
    </div>

    <div
        data-sortable
        data-sortable-is-sorting
        data-sortable-group-id="{{ $node->getId() }}"
        data-sortable-endpoint="{{ $manager->route('sort-index') }}"
        data-sortable-nested-endpoint="{{ $manager->route('move-index') }}"
        class="relative w-full px-4 py-3 mt-2.5 space-y-3.5 border border-dashed rounded-md border-grey-400 group is-nested"
    >
        @php
            $level++;
        @endphp

        @foreach($node->getChildNodes() as $child)
            @include('chief-table::nestable.table-node-sort', ['node' => $child])
        @endforeach
    </div>
</div>
