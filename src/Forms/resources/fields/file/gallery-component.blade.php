<div>
    <div class="flex gap-3 mb-4">
        <input class="p-3" type="text" wire:model.debounce.500ms="filters.search" placeholder="zoek op bestandsnaam">
    </div>

    {{ $this->table }}

    <div><livewire:chief-wire::file-edit parent-id="{{ $this->id }}" /></div>
    <div><livewire:chief-wire::asset-delete parent-id="{{ $this->id }}" /></div>
</div>
