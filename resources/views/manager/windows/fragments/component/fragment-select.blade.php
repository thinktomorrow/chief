<?php $hideSelectOptions = !isset($inOpenState) || !$inOpenState; ?>

<div data-fragment-select data-sortable-ignore class="relative w-full">
    <!-- plus icon -->
    @if($hideSelectOptions)
        <div
            data-fragment-select-open
            class="absolute flex justify-center w-full h-8 border-none cursor-pointer group"
            style="margin-top: -12px; z-index: 1;"
        >
            <div class="absolute transform scale-0 bg-white rounded-full link link-primary transition-150 group-hover:scale-100">
                <svg width="24" height="24"> <use xlink:href="#icon-add-circle"/> </svg>
            </div>
        </div>
    @endif

    <!-- select options: create new or add existing -->
    <div data-fragment-select-options
         class="{{ ($hideSelectOptions) ? 'hidden' : '' }} relative p-6 pop border-t border-grey-100"
    >
        @if($hideSelectOptions)
            <a data-fragment-select-close class="absolute top-0 right-0 m-6 cursor-pointer link link-primary">
                <x-chief-icon-label type="close"></x-chief-icon-label>
            </a>
        @endif

        <div class="flex items-center justify-center gutter-3">
            <div>
                <a
                    data-sidebar-trigger="addFragment"
                    href="{{ $ownerManager->route('fragments-select-new', $owner) }}"
                    class="flex flex-col items-center justify-center space-y-1 label label-primary label-xl hover:bg-primary-500 hover:text-white transition-75"
                >
                    <svg width="24" height="24"> <use xlink:href="#icon-document-add"/> </svg>

                    <span class="font-semibold">Nieuw fragment</span>
                </a>
            </div>

            <div>
                <a
                    data-sidebar-trigger="addFragment"
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
