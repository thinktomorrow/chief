<x-chief::dialog wired>
    @if($isOpen)
        @php $rows = $this->getTableRows(); @endphp

        <div class="flex items-stretch max-h-[80vh]">
            <div class="flex flex-col w-full gap-6 pr-12 mr-12 border-r border-grey-100">
                <div
                    x-cloak
                    x-data="{showAsList: @entangle('showAsList')}"
                    class="flex items-center justify-between gap-3 form-light shrink-0"
                >
                    <div class="flex items-start gap-3">
                        <div class="relative flex items-center justify-end">
                            <svg class="absolute w-5 h-5 pointer-events-none left-3 body-dark">
                                <use xlink:href="#icon-magnifying-glass"></use>
                            </svg>

                            <x-chief::input.text
                                wire:model.debounce.500ms="filters.search"
                                placeholder="Zoek op bestandsnaam"
                                class="w-64 pl-10"
                            />
                        </div>

                        <x-chief::input.select wire:model="sort" class="w-64">
                            <option value="created_at_desc">Datum laatst toegevoegd</option>
                            <option value="created_at_asc">Datum eerst toegevoegd</option>
                        </x-chief::input.select>
                    </div>

                    <div class="flex items-start gap-1.5">
                        <button type="button" wire:click="showAsGrid" class="p-2 rounded-full bg-grey-100 group">
                            <svg class="w-5 h-5 body-dark group-hover:text-primary-500"><use xlink:href="#icon-squares-2x2"></use></svg>
                        </button>

                        <button type="button" wire:click="showAsList" class="p-2 rounded-full bg-grey-100 group">
                            <svg class="w-5 h-5 body-dark group-hover:text-primary-500"><use xlink:href="#icon-bars-4"></use></svg>
                        </button>
                    </div>
                </div>

                <div class="w-full overflow-y-auto grow">
                    <div class="row-start-start gutter-3">
                        @foreach($rows as $i => $asset)
                            <div wire:key="{{ $i.'_'.$asset->id }}" class="w-1/2 lg:w-1/3 xl:w-1/4 2xl:w-1/5">
                                <div class="relative">
                                    <input
                                        wire:click="selectAsset('{{ $asset->id }}')"
                                        type="checkbox"
                                        class="absolute inset-0 appearance-none cursor-pointer peer"
                                    >

                                    <div class="w-full overflow-hidden aspect-square rounded-xl bg-grey-100 peer-checked:ring-inset peer-checked:ring-1 peer-checked:ring-primary-500 peer-checked:shadow-md peer-hover:ring-inset peer-hover:ring-1 peer-hover:ring-primary-500 p-[1px]">
                                        @if ($asset->getExtensionType() == "image")
                                            <img
                                                src="{{ $asset->getUrl('thumb') }}"
                                                alt="{{ $asset->getFileName() }}"
                                                class="object-contain w-full h-full rounded-xl"
                                            />
                                        @elseif($asset->getMimeType())
                                            <div class="flex items-center justify-center w-full h-full text-grey-500">
                                                {!! \Thinktomorrow\Chief\Admin\Mediagallery\MimetypeIcon::fromString($asset->getMimeType())->icon() !!}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- <div class="mt-4 space-y-1.5 leading-tight">
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
                                    </div> --}}

                                    {{-- <button wire:click="selectAsset('{{ $asset->id }}')" type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
                                        <x-chief::icon-button icon="icon-plus" color="grey" />
                                    </button> --}}
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
            </div>

            {{-- Sidebar --}}
            <div class="flex flex-col gap-6 overflow-y-auto w-72 shrink-0">
                <div class="shrink-0">
                    <p class="h6 h6-dark">Geselecteerde assets</p>
                </div>

                <div class="overflow-y-auto grow">
                    {{-- assets here ... --}}
                </div>

                <div class="shrink-0">
                    <button wire:click="save" type="button" class="justify-center w-full text-center btn btn-grey">
                        Voeg selectie toe
                    </button>
                </div>
            </div>
        </div>
    @endif
</x-chief::dialog>
