@if($this->showTabs())
    <x-slot name="actions">
        <x-chief::button wire:click="editItem({{ $item->getId() }})" variant="grey" size="sm">
            <x-chief::icon.settings />
        </x-chief::button>
    </x-slot>
@endif
