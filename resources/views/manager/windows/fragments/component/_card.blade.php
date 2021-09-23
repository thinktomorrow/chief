<div
    data-fragment
    data-sortable-id="{{ $model->fragmentModel()->id }}"
    class="relative w-full"
>
    <div class="space-y-4 {{ ($isNested ?? false) ? 'px-8 py-4' : 'p-8' }}">
        <div class="flex items-start justify-between">
            <div class="flex items-center space-x-2">
                <span data-sortable-handle class="cursor-pointer link link-primary">
                    <x-chief-icon-label icon="icon-drag"></x-chief-icon-label>
                </span>

                <span class="text-lg font-bold text-grey-900">
                    {{ ucfirst($model->adminConfig()->getModelName()) }}
                </span>

                @if($model->fragmentModel()->isOffline())
                    <span class="text-sm label label-error">Offline</span>
                @endif

                @if($model->fragmentModel()->isShared())
                    <span class="text-sm label label-warning">Gedeeld fragment</span>
                @endif
            </div>

            @adminCan('fragment-edit')
                <a
                    data-sidebar-trigger="fragments"
                    data-sortable-ignore
                    href="@adminRoute('fragment-edit', $owner, $model)"
                    class="flex-shrink-0 link link-primary"
                >
                    <x-chief-icon-label type="edit"></x-chief-icon-label>
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
