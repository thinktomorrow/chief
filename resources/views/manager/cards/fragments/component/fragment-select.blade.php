<div data-fragment-select
     data-sortable-ignore
     class="relative w-full">

    <!-- plus icon -->
    <div data-fragment-select-open
         class="absolute flex justify-center w-full h-8 border-none cursor-pointer z-1 group"
         style="margin-top: -12px;"
    >
        <div class="absolute transform scale-0 bg-white rounded-full link link-black transition-150 group-hover:scale-100">
            <svg width="24" height="24"> <use xlink:href="#icon-add-circle"/> </svg>
        </div>
    </div>

    <!-- select options: create new or add existing -->
    <div
        data-fragment-select-options
        class="relative hidden p-6 border-t pop border-grey-100"
    >
        <a data-fragment-select-close class="absolute top-0 right-0 m-6 cursor-pointer link link-primary">
            <x-icon-label type="close"></x-icon-label>
        </a>

        <div class="flex items-center justify-center gutter-3">
            <div>
                <a
                    data-sidebar-trigger="selectFragment"
                    href="{{ $ownerManager->route('fragments-select-new', $owner) }}"
                    class="flex flex-col items-center justify-center space-y-1 label label-primary label-xl hover:bg-primary-500 hover:text-white transition-75"
                >
                    <svg width="24" height="24"> <use xlink:href="#icon-document-add"/> </svg>

                    <span class="font-semibold">Nieuw fragment</span>
                </a>
            </div>

            <div>
                <a
                    data-sidebar-trigger="selectFragment"
                    href="{{ $ownerManager->route('fragments-select-existing', $owner) }}"
                    class="flex flex-col items-center justify-center space-y-1 label label-primary label-xl hover:bg-primary-500 hover:text-white transition-75"
                >
                    <svg width="24" height="24"> <use xlink:href="#icon-duplicate"/> </svg>

                    <span class="font-semibold">Bestaand fragment</span>
                </a>
            </div>
        </div>
    </div>
</div>
