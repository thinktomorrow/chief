<x-chief::dialog wired>
    @if($isOpen)

        <?php $rows = $this->getTableRows(); ?>

        <div x-cloak x-data="{showAsList: @entangle('showAsList')}" class="flex gap-3 mb-4">
            <input class="p-3" type="text" wire:model.debounce.500ms="filters.search"
                   placeholder="zoek op bestandsnaam">
            <x-chief::input.select wire:model="sort">
                <option value="created_at_desc">Datum laatst toegevoegd</option>
                <option value="created_at_asc">Datum eerst toegevoegd</option>
            </x-chief::input.select>

            <button type="button" wire:click="showAsGrid">GRID</button>
            <button type="button" wire:click="showAsList">LIST</button>
        </div>


        <div class="w-full">
            <div class="space-y-4 card">
                <div>
                    <div class="row gutter-3">
                        @foreach($rows as $i => $asset)
                            <div wire:key="{{ $i.'_'.$asset->id }}" class="w-1/2 lg:w-1/3 xl:w-1/4 2xl:w-1/5">

                                <div class="w-full overflow-hidden aspect-square rounded-xl bg-grey-100">
                                    @if ($asset->getExtensionType() == "image")
                                        <img
                                            src="{{ $asset->getUrl('thumb') }}"
                                            alt="{{ $asset->getFileName() }}"
                                            class="object-contain w-full h-full"
                                        />
                                    @elseif($asset->getMimeType())
                                        <div class="flex items-center justify-center w-full h-full text-grey-500">
                                            {!! \Thinktomorrow\Chief\Admin\Mediagallery\MimetypeIcon::fromString($asset->getMimeType())->icon() !!}
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-4 space-y-1.5 leading-tight">
                                    <a
                                        href="{{ $asset->getUrl() }}"
                                        title="{{ $asset->getFileName() }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="text-black"
                                    >
                                        {{ $asset->getFileName() }}
                                    </a>

                                    <p class="text-sm text-grey-500">
                                        {{ $asset->getSize() }} | {{ $asset->getMimeType() }}
                                    </p>
                                </div>

                                <button wire:click="selectAsset('{{ $asset->id }}')" type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
                                    <x-chief::icon-button icon="icon-plus" color="grey" />
                                </button>

                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($rows->total() > $rows->count())
                    <div>
                        {{ $rows->links() }}
                    </div>
                @endif
            </div>
        </div>

        <button wire:click="save" type="button">VOEG SELECTIE TOE</button>

    @endif
</x-chief::dialog>
