<div
    data-sortable-id="{{ $node->getId() }}"
    class="py-3 nested:ptl sortable-item sorting:nested:p-0 sorting:nested:space-y-4"
>
    <div class="flex items-start justify-between group">
        <div class="flex items-start gap-1">
            {{-- Sortable handle icon --}}
            <span
                data-sortable-show-when-sorting
                data-sortable-handle
                class="cursor-pointer link link-primary"
                style="margin-left: 0; margin-top: -2px; margin-right: 0.5rem"
            >
                <x-chief::icon-button icon="icon-chevron-up-down"/>
            </span>

            {{-- Arrow icon --}}
            <span
                data-sortable-hide-when-sorting
                class="hidden cursor-pointer link link-black nested:block"
                style="margin-left: -1.75rem; margin-top: -5px; margin-right: 0.5rem;"
            >
                <svg width="20" height="20"><use xlink:href="#icon-arrow-tl-to-br"/></svg>
            </span>

            {{-- Card label --}}
            <div class="flex flex-wrap gap-1" style="margin-top: 0.2rem;">
                <a
                    href="{{ $manager->route('edit', $node->getId()) }}"
                    title="{{ $node->getModel()->getPageTitle($node->getModel()) }}"
                    class="font-medium body-dark group-hover:underline"
                >
                    {{ $node->getModel()->getPageTitle($node->getModel()) }}
                </a>

                @if(\Thinktomorrow\Chief\Admin\Settings\Homepage::is($node->getModel()))
                    <span class="label label-xs label-primary">Home</span>
                @endif

                <x-chief-tags::tags :tags="$node->getModel()->getTags()" size="xs" threshold="3"/>

                @if(
                    $node->getModel() instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract
                    && !$node->getModel()->inOnlineState()
                )
                    <span class="inline mr-1 label label-xs label-error">Offline</span>
                @endif
            </div>
        </div>

        <div data-sortable-hide-when-sorting>
            @include('chief::manager._index._options', ['model' => $node->getModel()])
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
            @include('chief-table::nestable.node', ['node' => $child])
        @endforeach
    </div>
</div>
