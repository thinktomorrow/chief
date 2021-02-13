<div
    data-sortable-id="{{ $model->fragmentModel()->id }}"
    data-sortable-handle
    class="w-full px-12 py-6 space-y-2"
>
    <div class="flex justify-between items-center flex-grow">
        <div class="group flex items-center space-x-2 cursor-default">
            <span class="bg-primary-50 font-medium text-primary-900 py-1 px-2 rounded-lg">
                {{ ucfirst($model->managedModelKey()) }}
            </span>

            {{-- TODO: should show the actual fragment state --}}
            <div class="relative flex items-center">
                <div class="bg-success rounded-full min-h-2 min-w-2 transform group-hover:scale-0 transition-base"></div>
                <div class="absolute text-success font-medium transform scale-0 group-hover:scale-100 transition-base">Online</div>
            </div>
        </div>

        @adminCan('fragment-edit')
            <div class="flex-shrink-0 flex items-center cursor-pointer" data-sortable-ignore>
                <a
                    data-sidebar-fragments-edit
                    href="@adminRoute('fragment-edit', $model)"
                    class="flex items-center -mx-2"
                >
                    <div class="rounded-full p-2 text-grey-500 hover:text-primary-500 hover:bg-primary-50 transition-base">
                        <svg width="18" height="18"><use xlink:href="#icon-more"></use></svg>
                    </div>
                </a>
            </div>
        @endAdminCan
    </div>

    <div class="space-y-1">
        {!! $model->renderAdminFragment($owner, $loop, $fragments) !!}
    </div>

    {{-- <div class="flex justify-between bg-white py-8 px-12 space-x-8">
        <div
            data-sortable-handle
            class="flex items-center cursor-pointer -mx-2"
        >
            <div class="rounded-full p-2 text-grey-500 hover:text-primary-700 hover:bg-grey-50 transition duration-150 ease-in-out">
                <svg width="18" height="18"><use xlink:href="#menu"></use></svg>
            </div>
        </div>

        <div class="flex-grow flex flex-col justify-center">
            {!! $model->renderAdminFragment($owner, $loop, $fragments) !!}
        </div>

        @adminCan('fragment-edit')
            <div class="flex-shrink-0 flex items-center">
                <a
                    data-sidebar-fragments-edit
                    href="@adminRoute('fragment-edit', $model)"
                    class="flex items-center -mx-2"
                >
                    <div class="rounded-full p-2 text-grey-500 hover:text-primary-700 hover:bg-grey-50 transition duration-150 ease-in-out">
                        <svg width="18" height="18"><use xlink:href="#icon-more"></use></svg>
                    </div>
                </a>
            </div>
        @endAdminCan
    </div> --}}
</div>
