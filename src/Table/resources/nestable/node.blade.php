<div
    data-sortable-id="{{ $node->getId() }}"
    class="py-3 nested:ptl sortable-item sorting:nested:p-0 sorting:nested:space-y-4"
>
    <div class="flex items-start justify-between gap-4 group">
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
                style="margin-left: -1.75rem; margin-right: 0.5rem; margin-top: 0.2rem;"
            >
                <svg width="20" height="20"><use xlink:href="#icon-arrow-tl-to-br"/></svg>
            </span>

            {{-- Card label --}}
            <div class="flex flex-wrap gap-1 mt-[0.2rem] items-start">
                @adminCan('edit')
                    <a
                        href="{{ $manager->route('edit', $node->getId()) }}"
                        title="{{ $node->getModel()->getPageTitle($node->getModel()) }}"
                        class="mr-1 font-medium body-dark group-hover:underline"
                    >
                        {{ $node->getModel()->getPageTitle($node->getModel()) }}
                    </a>
                @elseAdminCan
                    <span class="mr-1 font-medium body-dark">
                        {{ $node->getModel()->getPageTitle($node->getModel()) }}
                    </span>
                @endAdminCan

                @if(\Thinktomorrow\Chief\Admin\Settings\Homepage::is($node->getModel()))
                    <span class="label label-xs label-primary mt-[1px]">Home</span>
                @endif

                @if(
                    $node->getModel() instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract
                    && !$node->getModel()->inOnlineState()
                )
                    <span class="label label-xs label-error mt-[1px]">Offline</span>
                @endif

                @if ($node->getModel() instanceof Thinktomorrow\Chief\Plugins\Tags\Application\Taggable\Taggable)
                    <x-chief-tags::tags :tags="$node->getModel()->getTags()" size="xs" threshold="4"/>
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
