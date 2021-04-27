<div
    data-fragment
    data-sortable-id="{{ $model->fragmentModel()->id }}"
    data-sortable-handle
    class="relative w-full p-8"
>
    <div class="space-y-2">
        <div class="flex justify-between items-start">
            <div class="flex items-center space-x-2">
                <span class="text-xl font-semibold text-grey-900">
                    {{ ucfirst($model->managedModelKey()) }}
                </span>

                @if($model->fragmentModel()->isOffline())
                    <span class="label label-error text-sm">Offline</span>
                @endif
            </div>

            @adminCan('fragment-edit')
                <a
                    data-sidebar-fragments-edit
                    data-sortable-ignore
                    href="@adminRoute('fragment-edit', $owner, $model)"
                    class="flex-shrink-0 link link-primary"
                >
                    <x-icon-label type="edit"></x-icon-label>
                </a>
            @endAdminCan
        </div>

        <div>
            {!! $model->renderAdminFragment($owner, $loop, $fragments) !!}
        </div>
    </div>
</div>
