<x-chief::dialog.modal wired size="xl" title="Kies uit de mediabibliotheek">
    @if ($isOpen)
        @php
            $rows = $this->getTableRows();
            $count = count($selectedPreviewFiles);
        @endphp

        <x-slot name="header">
            <div class="flex shrink-0 flex-wrap items-start justify-between gap-3 p-4">
                <div x-data="{}" class="relative flex grow items-center justify-end">
                    <x-chief::icon.search class="body-dark pointer-events-none absolute left-3 size-5" />

                    <x-chief::form.input.text
                        wire:model.live.debounce.500ms="filters.search"
                        x-data="{}"
                        {{-- Prevents directive to be triggered twice --}}
                        x-prevent-submit-on-enter
                        placeholder="Zoek op bestandsnaam"
                        class="w-full pl-10"
                    />
                </div>

                <x-chief::form.input.select wire:model.live="sort" class="w-64 shrink-0">
                    <option value="created_at_desc">Datum laatst toegevoegd</option>
                    <option value="created_at_asc">Datum eerst toegevoegd</option>
                </x-chief::form.input.select>
            </div>
        </x-slot>

        <div class="gutter-2 flex flex-wrap items-start justify-start">
            @foreach ($rows as $i => $asset)
                <div
                    wire:key="filefield_choose_{{ $i . '_' . $asset->id }}"
                    class="w-1/2 sm:w-1/3 md:w-1/4 xl:w-1/5 2xl:w-1/6"
                >
                    <div wire:click="selectAsset('{{ $asset->id }}')">
                        @include(
                            'chief-assets::_partials.asset-item',
                            [
                                'asset' => $asset,
                                'disabled' => in_array($asset->id, $existingAssetIds),
                                'active' => in_array($asset->id, $assetIds),
                                'withActions' => false,
                            ]
                        )
                    </div>
                </div>
            @endforeach
        </div>

        <x-slot name="footer">
            <div class="border-grey-100 space-y-4 border-t p-4">
                @if ($rows->total() > $rows->count())
                    <div class="shrink-0">
                        {{ $rows->onEachSide(0)->links() }}
                    </div>
                @endif

                <div class="flex shrink-0 justify-between gap-6 max-lg:flex-wrap">
                    <div class="flex items-center gap-5">
                        <p class="body text-grey-500 shrink-0 text-sm">
                            {{ $count }} {{ $count == 1 ? 'item' : 'items' }} geselecteerd
                        </p>

                        <div class="-mt-0.5 flex min-w-0 overflow-x-auto pr-2">
                            @foreach ($selectedPreviewFiles as $selectedPreviewFile)
                                <div class="-mr-2 shrink-0">
                                    <img
                                        src="{{ $selectedPreviewFile->getUrl('thumb') }}"
                                        alt="{{ $selectedPreviewFile->filename }}"
                                        class="bg-grey-100 h-10 w-10 rounded-lg border-2 border-white object-cover"
                                    />
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex shrink-0 flex-wrap justify-end gap-2 max-lg:w-full">
                        <x-chief::button wire:click="close" type="button" class="shrink-0">Annuleren</x-chief::button>

                        <x-chief::button variant="blue" wire:click="save" type="button" class="shrink-0">
                            Voeg selectie toe
                        </x-chief::button>
                    </div>
                </div>
            </div>
        </x-slot>
    @endif
</x-chief::dialog.modal>
