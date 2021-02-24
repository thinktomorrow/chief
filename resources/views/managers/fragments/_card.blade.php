<div
    data-sortable-id="{{ $model->fragmentModel()->id }}"
    data-sortable-handle
    class="group relative w-full px-12 py-6"
>
    <div class="space-y-2">
        <div class="flex justify-between items-center flex-grow">
            <div class="flex items-center space-x-2 cursor-default">
                <span class="bg-primary-50 font-medium text-grey-900 py-1 px-2 rounded-lg">
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
                        href="@adminRoute('fragment-edit', $model)"
                        class="link link-primary"
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

    <div data-sortable-ignore class="absolute z-10 inset-0 flex flex-col justify-between items-center pointer-events-none">
        <div
            data-sortable-insert="{{ $model->fragmentModel()->id }}"
            data-sortable-insert-position="before"
            class="flex items-center link link-black h-8 -mt-4 cursor-pointer pointer-events-auto transform scale-0 group-hover:scale-100 transition-300"
         >
            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
       </div>

        <div
            data-sortable-insert="{{ $model->fragmentModel()->id }}"
            data-sortable-insert-position="after"
            class="flex items-center link link-black h-8 -mb-4 cursor-pointer pointer-events-auto transform scale-0 group-hover:scale-100 transition-300"
        >
            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
       </div>
    </div>
</div>
