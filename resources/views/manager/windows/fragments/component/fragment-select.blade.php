@php
    $hideSelectOptions = ! isset($inOpenState) || ! $inOpenState;
@endphp

<div
    data-fragment-select
    data-sortable-ignore
    class="{{ $hideSelectOptions ? 'with-fragment-select-options' : null }} relative w-full"
>
    <!-- plus icon -->
    @if ($hideSelectOptions)
        <div class="absolute z-[1] flex h-8 w-full justify-center">
            <x-chief-table::button data-fragment-select-open size="sm" class="absolute -top-3.5">
                <x-chief::icon.plus-sign />
            </x-chief-table::button>
        </div>
    @endif

    <!-- select options: create new or add existing -->
    <div
        data-fragment-select-options
        class="{{ $hideSelectOptions ? 'hidden' : '' }} fragment-select-options relative border-t border-grey-100 py-6"
    >
        <div
            class="flex flex-col-reverse flex-wrap items-end justify-center gap-3 sm:flex-row sm:flex-nowrap sm:items-stretch"
        >
            <div class="w-7 shrink-0"></div>

            <div class="pop w-full">
                <a
                    data-sidebar-trigger
                    href="{{ $ownerManager->route('fragments-select-new', $owner) }}"
                    class="label label-primary label-xl transition-75 flex h-full flex-col items-center justify-center space-y-1 text-center hover:bg-primary-500 hover:text-white"
                >
                    <svg width="24" height="24"><use xlink:href="#icon-squares-plus" /></svg>

                    <span class="font-semibold">Nieuw fragment</span>
                </a>
            </div>

            <div class="pop w-full">
                <a
                    data-sidebar-trigger
                    href="{{ $ownerManager->route('fragments-select-existing', $owner) }}"
                    class="label label-primary label-xl transition-75 flex h-full flex-col items-center justify-center space-y-1 text-center hover:bg-primary-500 hover:text-white"
                >
                    <svg width="24" height="24"><use xlink:href="#icon-square-2-stack" /></svg>

                    <span class="font-semibold">Bestaand fragment</span>
                </a>
            </div>

            <div class="flex w-7 shrink-0 items-start justify-end">
                @if ($hideSelectOptions)
                    <x-chief-table::button data-fragment-select-close size="sm" variant="quaternary">
                        <x-chief::icon.cancel />
                    </x-chief-table::button>
                @endif
            </div>
        </div>
    </div>
</div>
