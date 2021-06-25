<div data-fragment-select
     data-sortable-ignore
     class="relative w-full">

    <!-- plus icon -->
    <div data-fragment-select-open
         class="absolute flex justify-center z-1 border-none group w-full h-8 cursor-pointer"
         style="margin-top: -12px;"
    >
        <div class="absolute link link-black bg-white rounded-full transition-150 transform scale-0 group-hover:scale-100">
            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
    </div>

    <!-- select options: create new or add existing -->
    <div data-fragment-select-options
         class="hidden relative p-6 pop border-t border-grey-100"
    >
        <a data-fragment-select-close class="absolute top-0 right-0 m-6 cursor-pointer link link-grey">
            <x-icon-label type="close"></x-icon-label>
        </a>

        <div class="flex items-center justify-center w-full gutter-2">
            <div>
                <a data-sidebar-trigger="selectFragment"
                   href="{{ $ownerManager->route('fragments-select-new', $owner) }}"
                   class="flex flex-col items-center justify-center space-y-1 label label-primary label-xl"
                >
                    <svg width="24" height="24">
                        <use xlink:href="#icon-add"/>
                    </svg>

                    <span>Maak een nieuw fragment</span>
                </a>
            </div>

            <div>
                <a data-sidebar-trigger="selectFragment"
                   href="{{ $ownerManager->route('fragments-select-existing', $owner) }}"
                   class="flex flex-col items-center justify-center space-y-1 label label-primary label-xl"
                >
                    <svg width="24" height="24">
                        <use xlink:href="#icon-duplicate"/>
                    </svg>

                    <span>Kies een bestaand fragment</span>
                </a>
            </div>
        </div>
    </div>
</div>
