@if($currentOwner)
    <button type="button" x-on:click="$dispatch('open-dialog', { 'id': 'file-owners-modal-{{ $this->getId() }}' })">
        <x-chief::button>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256"><path d="M190.63,65.37a32,32,0,0,0-45.19-.06L133.79,77.52a8,8,0,0,1-11.58-11l11.72-12.29a1.59,1.59,0,0,1,.13-.13,48,48,0,0,1,67.88,67.88,1.59,1.59,0,0,1-.13.13l-12.29,11.72a8,8,0,0,1-11-11.58l12.21-11.65A32,32,0,0,0,190.63,65.37ZM122.21,178.48l-11.65,12.21a32,32,0,0,1-45.25-45.25l12.21-11.65a8,8,0,0,0-11-11.58L54.19,133.93a1.59,1.59,0,0,0-.13.13,48,48,0,0,0,67.88,67.88,1.59,1.59,0,0,0,.13-.13l11.72-12.29a8,8,0,1,0-11.58-11ZM208,152H184a8,8,0,0,0,0,16h24a8,8,0,0,0,0-16ZM48,104H72a8,8,0,0,0,0-16H48a8,8,0,0,0,0,16Zm112,72a8,8,0,0,0-8,8v24a8,8,0,0,0,16,0V184A8,8,0,0,0,160,176ZM96,80a8,8,0,0,0,8-8V48a8,8,0,0,0-16,0V72A8,8,0,0,0,96,80Z"></path></svg>
            Bewerk koppeling
        </x-chief::button>
    </button>
@endif

@teleport('body')
    <x-chief::dialog id="file-owners-modal-{{ $this->getId() }}" title="Dit bestand wordt gebruikt op {{ $ownerCount }} plaatsen">
        <p class="body text-grey-500">
            Het aanpassen of vervangen van dit bestand is van toepassing op volgende
            {{ $ownerCount }} plaatsen:
        </p>

        <div class="mt-2 overflow-hidden border rounded-md border-grey-100">
            <div class="overflow-y-auto divide-y max-h-48 divide-grey-100">
                @foreach($previewFile->owners as $owner)
                    <div class="flex items-start justify-between gap-3 px-3 py-2.5">
                        <div class="leading-6 body-dark body">
                            @if(isset($modelReference) && $owner['modelReference'] == $modelReference)
                                <span class="font-bold">{{ $owner['label'] }}</span>
                                <span class="label label-xs label-grey">Deze koppeling</span>
                            @else
                                {{ $owner['label'] }}
                            @endif
                        </div>


                    @if(!isset($modelReference) || $owner['modelReference'] != $modelReference)
                            <a href="{{ $owner['adminUrl'] }}" title="Bekijk" target="_blank" rel="noopener">
                                <x-chief::link>
                                    <svg> <use xlink:href="#icon-external-link"></use> </svg>
                                </x-chief::link>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        @if($currentOwner)
            <p class="mt-4 body text-grey-500">
                Wil je aanpassingen enkel op deze plaats doorvoeren?
                Kies dan om het bestand te ontkoppelen en apart te bewerken.
            </p>
        @endif

        <x-slot name="footer">
            <div class="flex flex-wrap items-center justify-end gap-3">
                <button x-on:click="close" type="button" class="btn btn-grey">
                    Sluit
                </button>

                @if($currentOwner)
                    <button type="button" x-on:click="close(); $wire.isolateAsset();" class="btn btn-primary">
                        Ontkoppel en bewerk apart in {{ $currentOwner['label'] }}
                    </button>
                @endif
            </div>
        </x-slot>
    </x-chief::dialog>
@endteleport
