<div>
    <div x-cloak x-data="{showAsList: @entangle('showAsList')}" class="flex gap-3 mb-4">
        <input class="p-3" type="text" wire:model.debounce.500ms="filters.search" placeholder="zoek op bestandsnaam">
        <x-chief::input.select wire:model="sort">
            <option value="created_at_desc">Datum laatst toegevoegd</option>
            <option value="created_at_asc">Datum eerst toegevoegd</option>
        </x-chief::input.select>

        <button wire:click="showAsGrid">GRID</button>
        <button wire:click="showAsList">LIST</button>
    </div>

    {{ $this->table }}

    <div><livewire:chief-wire::file-edit parent-id="{{ $this->id }}" /></div>
    <div><livewire:chief-wire::asset-delete parent-id="{{ $this->id }}" /></div>
</div>
