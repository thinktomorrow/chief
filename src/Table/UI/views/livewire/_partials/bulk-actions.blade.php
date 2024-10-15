<div x-cloak x-show="selection.length > 0" class="flex w-full flex-wrap items-center justify-between gap-3">
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <span class="text-xs text-grey-500">
                <span x-text="selection.length"></span>
                geselecteerd
            </span>

            <div class="flex items-start gap-1">
                @if ($this->resultTotal > $this->resultPageCount && $this->resultTotal > count($this->bulkSelection))
                    <x-chief-table::button wire:click="bulkSelectAll" variant="outline-white" size="xs">
                        Selecteer alle {{ $this->resultTotal }}
                    </x-chief-table::button>
                @endif

                @if (count($this->bulkSelection) > 0)
                    <x-chief-table::button wire:click="bulkDeselectAll" variant="outline-white" size="xs">
                        Deselecteer alle {{ $this->resultTotal }}
                    </x-chief-table::button>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-end gap-1.5">
            @foreach ($this->getPrimaryBulkActions() as $action)
                <x-chief-table::action.button
                    :action="$action"
                    wire:click="applyAction('{{ $action->getKey() }}')"
                    size="xs"
                    variant="blue"
                />
            @endforeach

            @foreach ($this->getSecondaryBulkActions() as $action)
                <x-chief-table::action.button
                    :action="$action"
                    wire:click="applyAction('{{ $action->getKey() }}')"
                    size="xs"
                    variant="grey"
                />
            @endforeach

            @if (count($this->getTertiaryBulkActions()) > 0)
                <div>
                    <x-chief-table::button
                        x-on:click="$dispatch('open-dialog', { 'id': 'table-tertiary-bulk-actions' })"
                        size="xs"
                        variant="outline-white"
                    >
                        <x-chief::icon.more-vertical-circle />
                    </x-chief-table::button>

                    <x-chief::dialog.dropdown id="table-tertiary-bulk-actions" placement="bottom-end">
                        @foreach ($this->getTertiaryBulkActions() as $action)
                            <x-chief-table::action.dropdown.item
                                :action="$action"
                                wire:click="applyAction('{{ $action->getKey() }}')"
                            />
                        @endforeach
                    </x-chief::dialog.dropdown>
                </div>
            @endif
        </div>
    </div>
</div>
