@php
    $ownerCount = count($previewFile->owners);
    $currentOwner = null;

    foreach($previewFile->owners as $owner) {
        // dd($modelReference, $owner, $owner['modelReference']);

        if(isset($modelReference) && $owner['modelReference'] == $modelReference) {
            $currentOwner = $owner;
        }
    }
@endphp

{{-- Notification if more than one owner --}}
@if($ownerCount > 1)
    <div class="flex items-start gap-2 p-3 rounded-lg bg-primary-50">
        <svg class="w-6 h-6 text-primary-500 shrink-0"><use xlink:href="#icon-information-circle"></use></svg>

        <div class="mt-0.5">
            <p class="text-sm body text-primary-500">
                Dit bestand wordt getoond op {{ $ownerCount }} pagina's.
            </p>

            <p class="text-sm body">
                <button type="button">
                    <x-chief::link
                        x-on:click="$dispatch('open-dialog', { 'id': 'file-owners-modal-{{ $this->getId() }}' })"
                        underline
                        class="font-medium break-all text-primary-500"
                    >
                        {{ $currentOwner ? 'Aanpassen' : 'Bekijken' }}
                    </x-chief::link>
                </button>
            </p>
        </div>

    </div>

    @teleport('body')
        <x-chief::dialog id="file-owners-modal-{{ $this->getId() }}" title="Dit bestand is gekoppeld aan meerdere pagina's">
            <p class="body text-grey-500">
                Aanpassingen aan dit bestand of het vervangen van dit bestand is van toepassing op alle volgende {{ $ownerCount }} pagina's:
            </p>

            <div class="mt-2 overflow-hidden border rounded-md border-grey-100">
                <div class="overflow-y-auto divide-y max-h-48 divide-grey-100">
                    @foreach($previewFile->owners as $owner)
                        <div class="flex items-start justify-between gap-3 px-3 py-2.5">
                            <div class="leading-5 body-dark body">
                                {{ $owner['label'] }}

                                @if(isset($modelReference) && $owner['modelReference'] == $modelReference)
                                    <span class="label label-sm label-grey">Deze pagina</span>
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
                    Wil je aanpassingen enkel voor deze pagina doorvoeren?
                    Kies dan om het bestand te ontkoppelen en apart te bewerken.
                </p>
            @endif

            <x-slot name="footer">
                <div class="flex flex-wrap items-center justify-end gap-3">
                    <button x-on:click="close" type="button" class="btn btn-grey">
                        Sluiten
                    </button>

                    @if($currentOwner)
                        <button type="button" wire:click="isolateAsset" class="btn btn-primary">
                            Bewerk apart op deze pagina
                        </button>
                    @endif
                </div>
            </x-slot>
        </x-chief::dialog>
    @endteleport
{{-- Notification on mediagallery file edit if one and only one owner --}}
@elseif($ownerCount > 0 && !$currentOwner)
    <div class="flex items-start gap-2 p-3 rounded-lg bg-primary-50">
        <svg class="w-6 h-6 text-primary-500 shrink-0"><use xlink:href="#icon-information-circle"></use></svg>

        <p class="text-sm body text-primary-500 mt-0.5">
            Dit bestand wordt gebruikt op één pagina:
            @foreach($previewFile->owners as $owner)
                <a href="{{ $owner['adminUrl'] }}" title="Bekijk" target="_blank" rel="noopener">
                    <x-chief::link underline class="font-medium break-all text-primary-500">{{ $owner['label'] }}</x-chief::link>
                </a>
            @endforeach
        </p>
    </div>
@endif
