<div
    data-sortable-id="{{ $node->getId() }}"
    class="py-4 nested:ptl sortable-item sorting:nested:p-0 sorting:nested:space-y-4"
>
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            {{-- Sortable handle icon --}}
            <span
                data-sortable-show-when-sorting
                data-sortable-handle
                class="cursor-pointer link link-primary"
                style="margin-left: 0; margin-top: -2px; margin-right: 0.5rem"
            >
                <x-chief-icon-button icon="icon-drag"></x-chief-icon-button>
            </span>

            {{-- Arrow icon --}}
            <span
                data-sortable-hide-when-sorting
                class="hidden cursor-pointer link link-black nested:block"
                style="margin-left: -1.5rem; margin-top: -2px; margin-right: 0.5rem;"
            >
                <svg width="20" height="20"><use xlink:href="#icon-arrow-tl-to-br"/></svg>
            </span>

            {{-- Card label --}}
            <a
                href="{{ $manager->route('edit', $node->getId()) }}"
                title="{{ $node->getLabel() }}"
                class="text-lg leading-none display-dark display-base"
            >
                {{ $node->getLabel() }}

                @if(!$node->showOnline())
                    <span class="text-sm label label-warning font-weight-normal">Offline</span>
                @endif
            </a>
        </div>

        {{-- Edit icon --}}
        <div data-sortable-hide-when-sorting>
            <a
                href="{{ $manager->route('edit', $node->getId()) }}"
                title="Aanpassen"
                class="flex-shrink-0 link link-primary"
            >
                <x-chief-icon-button icon="icon-edit"></x-chief-icon-button>
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
                @include('chief-table::nestable.node', ['node' => $child])
            @endforeach
        </div>
</div>
