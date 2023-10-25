@if((isset($modelReference) && $ownerCount > 1) || (!isset($modelReference) && $ownerCount > 0))
    <div class="form-light">
        <x-chief::input.label>
            Dit bestand wordt getoond op
            {{ $ownerCount }}
            pagina's
        </x-chief::input.label>

        <a
                class="link link-primary"
                x-on:click="$dispatch('open-dialog', { 'id': 'file-owners-modal-{{ $this->getId() }}' })"
        >
            Aanpassen
        </a>
    </div>

    {{-- TODO: Placed here so the modal spans the entire screen. Should be implemented with x-teleport once Livewire is updated to v3. --}}
    <x-chief::dialog id="file-owners-modal-{{ $this->getId() }}" title="Bewaar een bestand met meerdere koppelingen">
        <div class="prose prose-dark prose-spacing">
            <p>
                Dit bestand is gekoppeld aan meerdere pagina's of fragmenten.
                De aanpassingen die je gaat bewaren zijn dus van toepassing voor alle gekoppelde plaatsen.
            </p>

            <p>
                Als je deze aanpassingen enkel wil doorvoeren op deze huidige locatie,
                kies er dan voor om het bestand los te koppelen en afzonderlijk te bewaren.
            </p>

            @if(isset($modelReference))
                <x-chief::input.description>
                    Aanpassingen zullen zichtbaar zijn op al deze pagina's.

                    <button type="button" wire:click="isolateAsset" class="underline link link-dark">
                        Koppel bestand los en bewerk afzonderlijk
                    </button>
                </x-chief::input.description>
            @endif

            <div class="overflow-hidden border rounded-md border-grey-100">
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
        </div>

        <x-slot name="footer">
            <div class="flex flex-wrap items-center justify-end gap-3">
                <button type="button" class="btn btn-grey">
                    Koppel bestand los en bewerk afzonderlijk
                </button>

                <p class="body body-dark">Of</p>

                <button wire:click.prevent="submit" type="submit" class="btn btn-primary">
                    Bewaar voor alle {{ $ownerCount }} pagina's
                </button>
            </div>
        </x-slot>
    </x-chief::dialog>

@endif
