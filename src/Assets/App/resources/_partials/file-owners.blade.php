@php
    $ownerCount = count($previewFile->owners);
    $currentOwner = null;

    foreach($previewFile->owners as $owner) {
        if(isset($modelReference) && $owner['modelReference'] == $modelReference){
            $currentOwner = $owner;
        }
    }
@endphp

{{-- Panel shown on a file field edit - with owner context --}}
@if($currentOwner && $ownerCount > 1)
    <div class="form-light">
        <x-chief::input.label>
            Dit bestand wordt getoond op
            {{ $ownerCount }}
            pagina's
        </x-chief::input.label>

        <a
                class="link link-primary cursor-pointer"
                x-on:click="$dispatch('open-dialog', { 'id': 'file-owners-modal-{{ $this->getId() }}' })"
        >
            Aanpassen
        </a>
    </div>

    {{-- TODO: Placed here so the modal spans the entire screen. Should be implemented with x-teleport once Livewire is updated to v3. --}}
    <x-chief::dialog id="file-owners-modal-{{ $this->getId() }}" title="Dit bestand is gekoppeld aan meerdere pagina's">
        <div class="prose prose-dark prose-spacing">
            <p>
                Aanpassingen aan dit bestand of het vervangen van dit bestand is van toepassing op alle volgende {{ $ownerCount }} pagina's:
            </p>

            <div class="overflow-hidden border rounded-md border-grey-100 my-6">
                <div class="overflow-y-auto divide-y max-h-48 divide-grey-100">
                    @foreach($previewFile->owners as $owner)
                        <div class="flex items-start justify-between gap-3 px-3 py-2">
                            <div class="leading-5 body-dark body">
                                {{ $owner['label'] }}
                                @if(isset($modelReference) && $owner['modelReference'] == $modelReference)
                                    <span class="label label-info text-xs">Deze pagina</span>
                                @endif
                            </div>


                            @if(!isset($modelReference) || $owner['modelReference'] != $modelReference)
                                <a href="{{ $owner['adminUrl'] }}" title="Bekijk" target="_blank"
                                   rel="noopener">
                                    <x-chief::link>
                                        <svg>
                                            <use xlink:href="#icon-external-link"></use>
                                        </svg>
                                    </x-chief::link>
                                </a>
                            @endif

                        </div>
                    @endforeach
                </div>
            </div>

            <p>
                Wil je aanpassingen enkel voor deze pagina doorvoeren?
                Kies dan om het bestand te ontkoppelen en apart te bewerken.
            </p>
        </div>

        <x-slot name="footer">
            <div class="flex flex-wrap items-center justify-end gap-3">
                <button type="button" wire:click="isolateAsset" class="btn btn-primary">
                    Bewerk apart {{ $currentOwner ? ' voor ' . $currentOwner['label'] : '' }}
                </button>

                <button x-on:click="close" type="button" class="btn">
                    Sluit
                </button>
            </div>
        </x-slot>
    </x-chief::dialog>

@endif

{{-- Panel shown on a mediagallery file edit - without owner context --}}
@if(!$currentOwner && $ownerCount > 0)
    <div class="form-light">
        <x-chief::input.label>
            Dit bestand wordt getoond op
            {{ $ownerCount }}
            pagina's
        </x-chief::input.label>

        <a
                class="link link-primary cursor-pointer"
                x-on:click="$dispatch('open-dialog', { 'id': 'file-owners-modal-{{ $this->getId() }}' })"
        >
            Aanpassen
        </a>
    </div>

    {{-- TODO: Placed here so the modal spans the entire screen. Should be implemented with x-teleport once Livewire is updated to v3. --}}
    <x-chief::dialog id="file-owners-modal-{{ $this->getId() }}" title="Dit bestand is gekoppeld aan meerdere pagina's">
        <div class="prose prose-dark prose-spacing">
            <p>
                Aanpassingen aan dit bestand of het vervangen van dit bestand is van toepassing op alle volgende {{ $ownerCount }} pagina's:
            </p>

            <div class="overflow-hidden border rounded-md border-grey-100 my-6">
                <div class="overflow-y-auto divide-y max-h-48 divide-grey-100">
                    @foreach($previewFile->owners as $owner)
                        <div class="flex items-start justify-between gap-3 px-3 py-2">
                            <div class="leading-5 body-dark body">
                                {{ $owner['label'] }}
                                @if(isset($modelReference) && $owner['modelReference'] == $modelReference)
                                    <span class="label label-info text-xs">Deze pagina</span>
                                @endif
                            </div>


                            @if(!isset($modelReference) || $owner['modelReference'] != $modelReference)
                                <a href="{{ $owner['adminUrl'] }}" title="Bekijk" target="_blank"
                                   rel="noopener">
                                    <x-chief::link>
                                        <svg>
                                            <use xlink:href="#icon-external-link"></use>
                                        </svg>
                                    </x-chief::link>
                                </a>
                            @endif

                        </div>
                    @endforeach
                </div>
            </div>

            <p>
                Wil je aanpassingen enkel voor deze pagina doorvoeren?
                Kies dan om het bestand te ontkoppelen en apart te bewerken.
            </p>
        </div>

        <x-slot name="footer">
            <div class="flex flex-wrap items-center justify-end gap-3">
                <button type="button" wire:click="isolateAsset" class="btn btn-primary">
                    Bewerk apart {{ $currentOwner ? ' voor ' . $currentOwner['label'] : '' }}
                </button>

                <button x-on:click="close" type="button" class="btn">
                    Sluit
                </button>
            </div>
        </x-slot>
    </x-chief::dialog>

@endif
