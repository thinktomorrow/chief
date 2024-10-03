<div x-cloak x-show="selection.length > 0" class="flex w-full flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-3">
        <span class="text-sm text-grey-500">
            <span x-text="selection.length"></span>
            geselecteerd
        </span>

        <div class="flex items-center justify-end gap-1.5">
            {{-- TODO(ben): get visible action, but not bulk actions --}}
            @foreach ($this->getVisibleBulkActions() as $action)
                {{ $action }}
            @endforeach

            @if (count($this->getHiddenBulkActions()) > 0)
                <div>
                    <button
                        type="button"
                        x-on:click="$dispatch('open-dialog', { 'id': 'table-hidden-bulk-actions' })"
                    >
                        <x-chief-table::button size="xs" color="white">
                            <x-chief::icon.more-vertical-circle />
                        </x-chief-table::button>
                    </button>

                    <x-chief::dialog.dropdown id="table-hidden-bulk-actions" placement="bottom-end">
                        @foreach ($this->getHiddenBulkActions() as $action)
                            {{ $action }}
                        @endforeach
                    </x-chief::dialog.dropdown>
                </div>
            @endif
        </div>
    </div>

    <div>
        @if ($this->resultTotal > $this->resultPageCount && $this->resultTotal > count($this->bulkSelection))
            <button
                type="button"
                wire:click="bulkSelectAll"
                class="text-sm font-medium text-grey-800 hover:underline hover:underline-offset-2"
            >
                Selecteer alle {{ $this->resultTotal }}
            </button>
        @endif

        <button
            x-show="selection.length > 0"
            type="button"
            wire:click="bulkDeselectAll"
            class="text-sm font-medium text-grey-800 hover:underline hover:underline-offset-2"
        >
            Deselecteer alle
        </button>
    </div>
</div>
