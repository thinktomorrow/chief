<div id="table-container-header-sorters" class="flex items-start justify-end gap-2">
    @if (count($this->getActiveFilters()) > 0)
        <div class="flex items-center justify-end gap-2">
            <span class="text-sm leading-5 text-grey-500">{{ $this->resultTotal }} items</span>
            <button type="button" class="mt-[0.1875rem]">
                <x-chief-table::button wire:click="resetFilters" color="grey" size="sm">
                    Reset filters
                </x-chief-table::button>
            </button>
        </div>
    @endif

    @if (count($this->getSortersForView()) > 1)
        <button type="button" x-on:click="$dispatch('open-dialog', { 'id': 'table-sorting' })">
            <x-chief-table::button color="white">
                <x-chief::icon.sorting />

                @if (count($this->getActiveSorters()) > 0)
                    @foreach ($this->getActiveSorters() as $sorter)
                        @if (! $sorter->hiddenFromView() && $sorter->showsActiveLabel())
                            <span>
                                {{ $sorter->getLabel() }}
                            </span>
                        @endif
                    @endforeach
                @endif
            </x-chief-table::button>
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
