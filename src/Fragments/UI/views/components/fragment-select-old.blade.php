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
        <div
            data-fragment-select-open
            class="absolute z-[1] mt-[-16px] flex h-8 w-full cursor-pointer justify-center border-none"
        >
            <div class="absolute">
                <x-chief::button>
                    <svg><use xlink:href="#icon-plus"></use></svg>
                </x-chief::button>
            </div>
        </div>
    @endif

    <!-- select options: create new or add existing -->
    <div
        data-fragment-select-options
        class="{{ $hideSelectOptions ? 'hidden' : '' }} pop fragment-select-options relative py-6"
    >
        <div
            class="flex flex-col-reverse flex-wrap items-end justify-center gap-4 sm:flex-row sm:flex-nowrap sm:items-stretch sm:gap-6"
        >
            <div class="w-8 shrink-0"></div>

            <div class="w-full">
                <a
                    data-sidebar-trigger
                    href="{{ route('chief::fragments.new', $context->id) }}"
                    class="label label-primary label-xl transition-75 flex h-full flex-col items-center justify-center space-y-1 text-center hover:bg-primary-500 hover:text-white"
                >
                    <svg width="24" height="24"><use xlink:href="#icon-squares-plus" /></svg>

                    <span class="font-semibold">Nieuw fragment</span>
                </a>
            </div>

            <div class="w-full">
                <a
                    data-sidebar-trigger
                    href="{{ route('chief::fragments.existing', $context->id) }}"
                    class="label label-primary label-xl transition-75 flex h-full flex-col items-center justify-center space-y-1 text-center hover:bg-primary-500 hover:text-white"
                >
                    <svg width="24" height="24"><use xlink:href="#icon-square-2-stack" /></svg>

                    <span class="font-semibold">Bestaand fragment</span>
                </a>
            </div>

            <div class="flex w-8 shrink-0 justify-end">
                @if ($hideSelectOptions)
                    <x-chief::button size="sm" data-fragment-select-close class="shrink-0">
                        <x-chief::icon.cancel />
                    </x-chief::button>
                @endif
            </div>
        </div>
    </div>
</div>
