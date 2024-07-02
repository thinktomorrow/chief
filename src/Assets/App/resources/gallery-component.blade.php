@php
    use Thinktomorrow\Chief\Plugins\ChiefPluginSections;
@endphp

<div>
    <div class="mb-4 flex w-full justify-end">
        <button
            wire:click="openFileUpload"
            type="button"
            class="btn btn-primary inline-flex items-start gap-2 leading-5"
        >
            <svg
                class="h-5 w-5"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Voeg bestanden toe
        </button>
    </div>

    <div class="card">
        <div class="space-y-6">
            <div class="flex flex-wrap gap-3">
                <div class="relative flex grow items-center justify-end">
                    <svg class="body-dark pointer-events-none absolute left-3 h-5 w-5">
                        <use xlink:href="#icon-magnifying-glass"></use>
                    </svg>

                    <x-chief::input.text
                        wire:model.live.debounce.500ms="filters.search"
                        placeholder="Zoek op bestandsnaam"
                        class="w-full pl-10"
                    />
                </div>

                <x-chief::input.select wire:model.live="sort">
                    <option value="created_at_desc">Datum laatst toegevoegd</option>
                    <option value="created_at_asc">Datum eerst toegevoegd</option>
                </x-chief::input.select>
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
    </div>
</div>
