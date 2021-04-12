<div
    data-fragment
    data-sortable-id="{{ $model->fragmentModel()->id }}"
    data-sortable-handle
    class="group relative w-full px-12 py-6"
>
    <div class="space-y-2">
        <div class="flex justify-between items-center flex-grow">
            <div class="flex items-center space-x-2 cursor-default">
                <span class="text-lg font-semibold text-grey-900">
                    {{ ucfirst($model->managedModelKey()) }}
                </span>

                {{-- TODO: should show the actual fragment state --}}
                <div class="relative flex items-center">
                    <div class="bg-success rounded-full min-h-2 min-w-2 transform group-hover:scale-0 transition-150"></div>
                    <div class="absolute text-success font-medium transform scale-0 group-hover:scale-100 transition-150">Online</div>
                </div>
            </div>

            @adminCan('fragment-edit')
                <div class="flex-shrink-0 flex items-center cursor-pointer" data-sortable-ignore>
                    <a
                        data-sidebar-fragments-edit
                        href="@adminRoute('fragment-edit',$owner, $model)"
                        class="link link-black"
                    >
                        <x-link-label type="edit"></x-link-label>
                    </a>
                </div>
            @endAdminCan
        </div>

        <div>
            {!! $model->renderAdminFragment($owner, $loop, $fragments) !!}
        </div>
    </div>
</div>
