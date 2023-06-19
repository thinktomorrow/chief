<x-chief::dialog wired>
    @if($isOpen)
        @php
            $rows = $this->getTableRows();
            $count = count($selectedPreviewFiles);
        @endphp

        <div class="w-full lg:w-[56rem] max-h-[80vh] flex flex-col gap-6 overflow-y-auto">
            <div class="flex flex-wrap items-start justify-between gap-3 shrink-0">
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

                <x-chief::input.select wire:model="sort" class="w-64 shrink-0">
                    <option value="created_at_desc">Datum laatst toegevoegd</option>
                    <option value="created_at_asc">Datum eerst toegevoegd</option>
                </x-chief::input.select>
            </div>

            <div class="w-full overflow-y-auto grow">
                <div class="row-start-start gutter-2">
                    @foreach($rows as $i => $asset)
                        <div wire:key="{{ $i.'_'.$asset->id }}" class="w-1/2 xs:w-1/3 sm:w-1/4 md:w-1/5 lg:w-1/6">
                            <div wire:click="selectAsset('{{ $asset->id }}')" class="space-y-3">
                                <div @class([
                                    'w-full overflow-hidden aspect-square rounded-xl bg-grey-100 p-[1px] cursor-pointer',
                                    'hover:ring-inset hover:ring-1 hover:ring-primary-500',
                                    'ring-inset ring-1 ring-primary-500 shadow-md' => in_array($asset->id, $assetIds),
                                ])>
                                    @if ($asset->isImage())
                                        <img
                                            src="{{ $asset->getUrl('thumb') }}"
                                            alt="{{ $asset->getFileName() }}"
                                            class="object-contain w-full h-full rounded-lg"
                                        />
                                    @elseif($asset->getMimeType())
                                        <div class="flex items-center justify-center w-full h-full text-grey-500">
                                            {!! \Thinktomorrow\Chief\Admin\Mediagallery\MimetypeIcon::fromString($asset->getMimeType())->icon() !!}
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-0.5">
                                    <p class="overflow-hidden text-sm body body-dark text-ellipsis whitespace-nowrap">
                                        {{ $asset->getFileName() }}
                                    </p>

                                    <div class="flex justify-between">
                                        <p class="text-xs body text-grey-500">
                                            {{ $asset->getHumanReadableSize() }}
                                        </p>

                                        <p class="text-xs uppercase body text-grey-500">
                                            {{ $asset->getExtension() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($rows->total() > $rows->count())
                <div class="shrink-0">
                    {{ $rows->links() }}
                </div>
            @endif

            <div class="flex justify-between gap-6 max-lg:flex-wrap shrink-0">
                <div class="flex items-center gap-5">
                    <p class="text-sm text-grey-500 body shrink-0">
                        {{ $count }} {{ $count == 1 ? 'item' : 'items' }} geselecteerd
                    </p>

                    <div class="flex -mt-0.5 min-w-0 overflow-x-auto pr-2">
                        @foreach($selectedPreviewFiles as $selectedPreviewFile)
                            <div class="-mr-2 shrink-0">
                                <img
                                    src="{{ $selectedPreviewFile->getUrl('thumb') }}"
                                    alt="{{ $selectedPreviewFile->filename }}"
                                    class="object-cover w-10 h-10 border-2 border-white rounded-lg bg-grey-100"
                                >
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-wrap justify-end gap-3 max-lg:w-full shrink-0">
                    <button wire:click="save" type="button" class="btn btn-grey shrink-0">
                        Annuleren
                    </button>

                    <button wire:click="save" type="button" class="btn btn-primary shrink-0">
                        Voeg selectie toe
                    </button>
                </div>
            </div>
        </div>
    @endif
</x-chief::dialog>
