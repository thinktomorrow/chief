<div class="xs-column-12 s-column-12 m-column-12">
    <div class="flex items-center justify-between bg-white border border-grey-100 rounded-lg px-6 py-4">
        <div class="flex-grow">
            {!! $model->renderAdminFragment($owner, $loop, $fragments) !!}
        </div>

        @adminCan('fragment-edit')
            <div class="flex-shrink-0 flex items-center">
                <a
                    data-edit-modal
                    href="@adminRoute('fragment-edit', $model)"
                    class="flex items-center mr-2"
                >
                    <div
                        class="
                            rounded-full p-2 bg-grey-50 hover:bg-primary-100 text-grey-500 hover:text-primary-500
                            transform hover:scale-110 transition duration-150 ease-in-out
                        "
                    >
                        <svg width="16" height="16"><use xlink:href="#icon-edit"></use></svg>
                    </div>
                </a>

                {{--
                    This button needs to open a delete confirmation popup.
                    Atm it also opens the edit sidebar.
                --}}
                <a
                    data-edit-modal
                    href="@adminRoute('fragment-edit', $model)"
                    class="flex items-center"
                >
                    <div
                        class="
                            rounded-full p-2 bg-grey-50 hover:bg-grey-100 text-grey-500 hover:text-error
                            transform hover:scale-110 transition duration-150 ease-in-out
                        "
                    >
                        <svg width="16" height="16"><use xlink:href="#trash"></use></svg>
                    </div>
                </a>
            </div>
        @endAdminCan
    </div>
</div>
