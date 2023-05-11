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
            class="absolute flex justify-center w-full h-8 border-none cursor-pointer"
            style="margin-top: -14px; z-index: 1;"
        >
            <div class="absolute">
                <x-chief::icon-button icon="icon-plus" color="grey" />
            </div>
        </div>
    @endif

    <!-- select options: create new or add existing -->
    <div
        data-fragment-select-options
        class="{{ ($hideSelectOptions) ? 'hidden' : '' }} relative py-6 pop fragment-select-options"
    >
        <div class="flex flex-col-reverse flex-wrap items-end justify-center gap-4 sm:gap-6 sm:items-stretch sm:flex-nowrap sm:flex-row">
            <div class="w-8 shrink-0"></div>

            <div class="w-full">
                <a
                    data-sidebar-trigger
                    href="{{ $ownerManager->route('fragments-select-new', $owner) }}"
                    class="flex flex-col items-center justify-center h-full space-y-1 text-center label label-primary label-xl hover:bg-primary-500 hover:text-white transition-75"
                >
                    <svg width="24" height="24"> <use xlink:href="#icon-squares-plus"/> </svg>

                    <span class="font-semibold">Nieuw fragment</span>
                </a>
            </div>

            <div class="w-full">
                <a
                    data-sidebar-trigger
                    href="{{ $ownerManager->route('fragments-select-existing', $owner) }}"
                    class="flex flex-col items-center justify-center h-full space-y-1 text-center label label-primary label-xl hover:bg-primary-500 hover:text-white transition-75"
                >
                    <svg width="24" height="24"> <use xlink:href="#icon-square-2-stack"/> </svg>

                    <span class="font-semibold">Bestaand fragment</span>
                </a>
            </div>

            <div class="flex justify-end w-8 shrink-0">
                @if($hideSelectOptions)
                    <span data-fragment-select-close class="cursor-pointer">
                        <x-chief::icon-button icon="icon-x-mark" color="grey" />
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
