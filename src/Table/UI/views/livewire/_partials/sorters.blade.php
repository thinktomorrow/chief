<div id="table-container-header-sorters" class="flex items-start justify-end gap-2">

    @if (count($this->getActiveFilters()) > 0)
        <div class="flex items-center justify-end gap-2">
            <span class="text-sm leading-5 text-grey-500">
                {{ $this->resultTotal }} items
            </span>
            <button type="button" class="mt-[0.1875rem]">
                <x-chief-table::button wire:click="resetFilters" color="grey" size="sm">
                    Reset filters
                </x-chief-table::button>
            </button>
        </div>

    @endif

    @if (count($this->getSortersForView()) > 1)
        <button type="button" x-on:click="$dispatch('open-dialog', { 'id': 'table-sorting' })">
            <x-chief-table::filter.select
                iconLeft='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M11 10L18 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M11 14H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M11 18H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M11 6H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M7 18.8125C6.60678 19.255 5.56018 21 5 21M3 18.8125C3.39322 19.255 4.43982 21 5 21M5 21L5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M3 5.1875C3.39322 4.74501 4.43982 3 5 3M7 5.1875C6.60678 4.74501 5.56018 3 5 3M5 3L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
                iconRight=""
            >
                @if (count($this->getActiveSorters()) > 0)
                    @foreach ($this->getActiveSorters() as $sorter)
                        @if (! $sorter->hiddenFromView() && $sorter->showsActiveLabel())
                            {{ $sorter->getLabel() }}
                        @endif
                    @endforeach
                @endif
            </x-chief-table::filter.select>
        </button>

        <x-chief::dialog.dropdown id="table-sorting" placement="bottom-end">
            <div
                x-on:change="
                    close()
                    $wire.addSorter()
                "
                class="min-w-48 space-y-3.5 p-3.5"
            >
                <div class="space-y-2">
                    @foreach ($this->getSortersForView() as $sorter)
                        {!! $sorter->render() !!}
                    @endforeach
                </div>

                <div class="flex items-start justify-between gap-2">
                    <button type="button" x-on:click="close()">
                        <x-chief-table::button size="sm" color="white">Annuleer</x-chief-table::button>
                    </button>
                </div>
            </div>
        </x-chief::dialog.dropdown>
    @endif
</div>
