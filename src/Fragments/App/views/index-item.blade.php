<div data-fragment data-sortable-id="{{ $fragment->getFragmentId() }}" class="w-full">
    <div class="py-6 space-y-4">
        <div class="flex items-stretch justify-end space-x-3">
            <div data-sortable-handle class="cursor-pointer shrink-0">
                <x-chief::icon-button icon="icon-chevron-up-down" color="grey" />
            </div>

            <div class="w-full mt-0.5 space-x-1">
                <span class="text-lg h6 h1-dark">
                    {{ ucfirst($fragment->getLabel()) }}
                </span>

                <span class="align-bottom with-xs-labels">
                    @if($fragment->fragmentModel()->isOffline())
                        <span class="label label-error"> Offline </span>
                    @endif

                    @if($fragment->fragmentModel()->isShared())
                        <span class="label label-warning"> Gedeeld fragment </span>
                    @endif
                </span>
            </div>
                <a
                    data-sidebar-trigger
                    href="{{ route('chief::fragments.edit', [$context->id, $fragment->getFragmentId()]) }}"
                    title="Fragment aanpassen"
                    class="shrink-0"
                >
                    <x-chief::icon-button icon="icon-edit"/>
                </a>
        </div>

        @if($adminFragment = $fragment->renderAdminFragment($owner, $loop))
            <div class="px-[2.65rem]">
                {!! $adminFragment !!}
            </div>
        @endif
    </div>

    @include('chief-fragments::components.fragment-select')
</div>
