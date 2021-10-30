<div data-fragment data-sortable-id="{{ $model->fragmentModel()->id }}" class="w-full">
    <div class="py-6 space-y-3">
        <div class="flex items-stretch justify-end space-x-3">
            <div class="flex-shrink-0">
                <span
                    data-sortable-handle
                    class="inline-block p-1.5 rounded-xl bg-primary-50 icon-label link link-primary"
                >
                    <x-chief-icon-label icon="icon-drag" size="18"></x-chief-icon-label>
                </span>
            </div>

            <div class="w-full mt-0.5 space-x-1">
                <span class="text-lg font-semibold leading-normal text-black">
                    {{ ucfirst($model->adminConfig()->getModelName()) }}
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
                <div class="flex-shrink-0">
                    <a
                        data-sortable-ignore
                        data-sidebar-trigger="fragments"
                        href="@adminRoute('fragment-edit', $owner, $model)"
                        title="Fragment aanpassen"
                        class="inline-block p-1.5 rounded-xl bg-primary-50 icon-label link link-primary"
                    >
                        <x-chief-icon-label type="edit" size="18"></x-chief-icon-label>
                    </a>
                </div>
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
