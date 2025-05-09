@php
    use Thinktomorrow\Chief\Plugins\ChiefPluginSections;
@endphp

<div class="space-y-4">
    <div class="flex justify-end">
        <x-chief::button wire:click="openFileUpload" variant="blue">
            <x-chief::icon.plus-sign />
            <span>Voeg bestanden toe</span>
        </x-chief::button>
    </div>

    <x-chief::window>
        <div class="space-y-6">
            <div class="flex flex-wrap gap-3">
                <div class="relative flex grow items-center justify-end">
                    <x-chief::icon.search class="body-dark pointer-events-none absolute left-3 size-5" />

                    <x-chief::form.input.text
                        wire:model.live.debounce.500ms="filters.search"
                        placeholder="Zoek op bestandsnaam"
                        class="w-full pl-10"
                    />
                </div>

                <x-chief::form.input.select wire:model.live="sort">
                    <option value="created_at_desc">Datum laatst toegevoegd</option>
                    <option value="created_at_asc">Datum eerst toegevoegd</option>
                </x-chief::form.input.select>
            </div>

            {{ $this->table }}
        </div>

        <div>
            <livewire:chief-wire::file-upload
                parent-id="{{ $this->getId() }}"
                field-name="files"
                :allow-multiple="true"
            />
        </div>

        <div>
            <livewire:chief-wire::file-edit parent-id="{{ $this->getId() }}" />
        </div>

        <div>
            <livewire:chief-wire::asset-delete parent-id="{{ $this->getId() }}" />
        </div>

        @foreach (app(ChiefPluginSections::class)->getLivewireFileComponents() as $livewireFileComponent)
            <div>
                <livewire:is component="{{ $livewireFileComponent }}" parent-id="{{ $this->getId() }}" />
            </div>
        @endforeach
    </x-chief::window>
</div>
