<div class="xs-column-12 s-column-12 m-column-12" data-sortable-id="{{ $model->fragmentModel()->id }}">
    <div class="flex justify-between bg-white border border-grey-100 rounded-lg p-6 space-x-8">
        <div class="flex items-center cursor-pointer -mx-2" data-sortable-handle>
            <div
                class="
                    rounded-full p-2 hover:bg-grey-50 text-grey-500 hover:text-primary-700
                    transform hover:scale-110 transition duration-150 ease-in-out
                "
            >
                <svg width="16" height="16"><use xlink:href="#menu"></use></svg>
            </div>
        </div>

        <div class="flex-grow">
            {!! $model->renderAdminFragment($owner, $loop, $fragments) !!}
        </div>

        @adminCan('fragment-edit')
            <div class="flex-shrink-0">
                <a
                    data-sidebar-fragment-edit
                    href="@adminRoute('fragment-edit', $model)"
                    class="flex items-center -mx-2"
                >
                    <div
                        class="
                            rounded-full p-2 hover:bg-grey-50 text-grey-500 hover:text-primary-700
                            transform hover:scale-110 transition duration-150 ease-in-out
                        "
                    >
                        <svg width="16" height="16"><use xlink:href="#icon-more"></use></svg>
                    </div>
                </a>
            </div>
        @endAdminCan
    </div>
</div>
