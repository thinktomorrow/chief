<div class="card">
    <div class="space-y-6">
        <div class="flex flex-wrap gap-3 form-light">
            <div class="relative flex items-center justify-end grow">
                <svg class="absolute w-5 h-5 pointer-events-none left-3 body-dark">
                    <use xlink:href="#icon-magnifying-glass"></use>
                </svg>

                <x-chief::input.text
                    wire:model.debounce.500ms="filters.search"
                    placeholder="Zoek op bestandsnaam"
                    class="w-full pl-10"
                />
            </div>

            <x-chief::input.select wire:model="sort">
                <option value="created_at_desc">Datum laatst toegevoegd</option>
                <option value="created_at_asc">Datum eerst toegevoegd</option>
            </x-chief::input.select>
        </div>

        {{ $this->table }}
    </div>

    <div><livewire:chief-wire::file-edit parent-id="{{ $this->id }}"/></div>
    <div><livewire:chief-wire::asset-delete parent-id="{{ $this->id }}"/></div>
</div>
