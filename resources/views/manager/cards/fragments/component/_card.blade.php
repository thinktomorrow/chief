<div
    data-fragment
    data-sortable-id="{{ $model->fragmentModel()->id }}"
    class="relative w-full p-8"
>
    <div class="space-y-4">
        <div class="flex items-start justify-between">
            <div class="flex items-center space-x-2">
                <span data-sortable-handle class="cursor-pointer link link-primary">
                    <x-icon-label icon="icon-drag"></x-icon-label>
                </span>

                <span class="text-xl font-semibold text-grey-900">
                    {{ ucfirst($model->adminConfig()->getPageTitle()) }} (sort: {{ $model->fragmentModel()->pivot->order }})
                </span>

                @if($model->fragmentModel()->isOffline())
                    <span class="text-sm label label-error">Offline</span>
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
</div>
