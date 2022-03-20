@php
    $hideSelectOptions = !isset($inOpenState) || !$inOpenState;
@endphp

<div
    data-fragment-select
    data-sortable-ignore
    class="relative w-full {{ $hideSelectOptions ? 'with-fragment-select-options' : null }}"
>
    <!-- plus icon -->
    @if($hideSelectOptions)
        <div
            data-fragment-select-open
            class="absolute flex justify-center w-full h-8 border-none cursor-pointer group"
            style="margin-top: -14px; z-index: 1;"
        >
            <div class="absolute transition-all duration-75 ease-in scale-95 group-hover:scale-100">
                <x-chief-icon-button icon="icon-add" color="grey" />
            </div>
        </div>
    @endif

    <!-- select options: create new or add existing -->
    <div
        data-fragment-select-options
        class="{{ ($hideSelectOptions) ? 'hidden' : '' }} relative py-6 pop fragment-select-options"
    >
        @if($hideSelectOptions)
            <span data-fragment-select-close class="absolute right-0 cursor-pointer top-3 link link-primary">
                <x-chief-icon-button icon="x" class="shadow-sm" />
            </span>
        @endif

        <div class="flex items-center justify-center gutter-3">
            <div class="w-full">
                <a
                    data-sidebar-trigger
                    href="{{ $ownerManager->route('fragments-select-new', $owner) }}"
                    class="flex flex-col items-center justify-center space-y-1 label label-primary label-xl hover:bg-primary-500 hover:text-white transition-75"
                >
                    <svg width="24" height="24"> <use xlink:href="#icon-document-add"/> </svg>

                    <span class="font-semibold">Nieuw fragment</span>
                </a>
            </div>

            <div class="w-full">
                <a
                    data-sidebar-trigger
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
