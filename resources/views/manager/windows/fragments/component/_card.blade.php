<div data-fragment data-sortable-id="{{ $model->fragmentModel()->id }}" class="w-full">
    <div class="py-6 space-y-4">
        <div class="flex items-stretch justify-end space-x-3">
            <div data-sortable-handle class="cursor-pointer shrink-0">
                <x-chief::icon-button icon="icon-chevron-up-down" color="grey" />
            </div>

            <div class="w-full mt-0.5 space-x-1">
                <span class="text-lg h6 h1-dark">
                    {{ ucfirst($resource->getLabel()) }}
                </span>

                <span class="align-bottom with-xs-labels">
                    @if($model->fragmentModel()->isOffline())
                        <span class="label label-error"> Offline </span>
                    @endif

                    @if($model->fragmentModel()->isShared())
                        <span class="label label-warning"> Gedeeld fragment </span>
                    @endif
                </span>
            </div>

            @adminCan('fragment-edit')
                <a
                    data-sidebar-trigger="fragments"
                    href="@adminRoute('fragment-edit', $owner, $model)"
                    title="Fragment aanpassen"
                    class="shrink-0"
                >
                    <x-chief::icon-button icon="icon-edit"/>
                </a>
            @endAdminCan
        </div>

        @if($adminFragment = $model->renderAdminFragment($owner, $loop))
            <div>
                {!! $adminFragment !!}
            </div>
        @endif
    </div>

    @include('chief::manager.windows.fragments.component.fragment-select')
</div>
