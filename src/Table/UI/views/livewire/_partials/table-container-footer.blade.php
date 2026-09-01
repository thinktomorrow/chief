@if ($this->hasPagination() && ($this->resultTotal > $this->resultPageCount || $this->shouldShowItemsPerPageSelection()))
    <div class="flex gap-2 px-4 py-2.5 sm:flex-row sm:items-center">
        @if ($this->shouldShowItemsPerPageSelection())
            <x-chief::form.input.select
                wire:model.change.number="selectedItemsPerPage"
                aria-label="Aantal resultaten per pagina"
                size="sm"
            >
                @foreach ($this->getItemsPerPageSelection() as $itemsPerPage)
                    <option value="{{ $itemsPerPage }}">{{ $itemsPerPage }}</option>
                @endforeach
            </x-chief::form.input.select>
        @endif

        <div class="min-w-0 flex-1">{{ $results->onEachSide(0)->links() }}</div>
    </div>
@endif
