<div
    data-fragment
    data-sortable-id="{{ $model->fragmentModel()->id }}"
    data-sortable-handle
    class="group relative w-full p-8"
>
    <div class="space-y-2">
        <div class="flex justify-between items-start">
            <div class="flex space-x-2 cursor-default">
                <span class="text-lg font-semibold text-grey-900">
                    {{ ucfirst($model->managedModelKey()) }}
                </span>

                {{-- TODO: should show the actual fragment state --}}
                <span class="label label-success text-sm">Online</span>
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
