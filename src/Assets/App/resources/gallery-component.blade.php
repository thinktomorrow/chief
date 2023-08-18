<div>
    <div class="flex justify-end w-full mb-4">
        <button
            wire:click="openFileUpload"
            type="button"
            class="inline-flex items-start gap-2 leading-5 btn btn-primary"
        >
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /> </svg>
            Voeg bestanden toe
        </button>
    </div>

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

        <div>
            <livewire:chief-wire::file-upload parent-id="{{ $this->id }}" field-name="files" :allow-multiple="true"/>
        </div>

        <div>
            <livewire:chief-wire::file-edit parent-id="{{ $this->id }}"/>
        </div>

        <div>
            <livewire:chief-wire::asset-delete parent-id="{{ $this->id }}"/>
        </div>

        @foreach(app(\Thinktomorrow\Chief\Plugins\ChiefPluginSections::class)->getLivewireFileComponents() as $livewireFileComponent)
            <div>
                <livewire:is component="{{ $livewireFileComponent }}" parent-id="{{ $this->id }}" />
            </div>
        @endforeach
    </div>
</div>
