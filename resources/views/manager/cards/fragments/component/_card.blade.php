<div
    data-fragment
    data-sortable-id="{{ $model->fragmentModel()->id }}"
    class="relative w-full"
>
    <div class="space-y-4 {{ ($isNested ?? false) ? 'px-12 py-4' : 'p-6' }}">
        <div class="flex items-start justify-between">
            <div class="flex items-center space-x-2">
                <span data-sortable-handle class="cursor-pointer link link-primary">
                    <x-icon-label icon="icon-drag"></x-icon-label>
                </span>

                <span class="text-lg font-semibold text-grey-900">
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
                    <x-icon-label type="edit"></x-icon-label>
                </a>
            @endAdminCan
        </div>

        <div>
            {!! $model->renderAdminFragment($owner, $loop) !!}
        </div>
    </div>

    @include('chief::manager.cards.fragments.component.fragment-select')

</div>
