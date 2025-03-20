@if ($currentOwner && $ownerCount > 1)
    <x-chief-table::button x-on:click="$dispatch('open-dialog', { 'id': 'file-owners-modal-{{ $this->getId() }}' })">
        <span>Bewerk koppeling</span>
    </x-chief-table::button>
@endif

@teleport('body')
    <x-chief::dialog.modal
        id="file-owners-modal-{{ $this->getId() }}"
        title="Dit bestand wordt gebruikt op {{ $ownerCount == 1 ? ' één plaats' : $ownerCount . ' plaatsen' }}"
    >
        <p class="body text-grey-500">
            Het aanpassen of vervangen van dit bestand is van toepassing op volgende
            {{ $ownerCount == 1 ? 'plaats' : 'plaatsen' }}:
        </p>

        <div class="mt-2 overflow-hidden rounded-md border border-grey-100">
            <div class="max-h-48 divide-y divide-grey-100 overflow-y-auto">
                @foreach ($previewFile->owners as $owner)
                    <div class="flex items-start justify-between gap-3 px-3 py-2.5">
                        <div class="body-dark body leading-6">
                            @if (isset($modelReference) && $owner['modelReference'] == $modelReference)
                                <span class="font-bold">{{ $owner['label'] }}</span>
                                <span class="label label-xs label-grey">Deze koppeling</span>
                            @else
                                {{ $owner['label'] }}
                            @endif
                        </div>

                        @if (! isset($modelReference) || $owner['modelReference'] != $modelReference)
                            <a href="{{ $owner['adminUrl'] }}" title="Bekijk" target="_blank" rel="noopener">
                                <x-chief::link>
                                    <svg><use xlink:href="#icon-external-link"></use></svg>
                                </x-chief::link>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        @if ($currentOwner && $ownerCount > 1)
            <p class="body mt-4 text-grey-500">
                Wil je aanpassingen enkel op deze plaats doorvoeren? Kies dan om het bestand te ontkoppelen en apart te
                bewerken.
            </p>
        @endif

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief-table::button x-on:click="close" type="button">Sluit</x-chief-table::button>

                @if ($currentOwner && $ownerCount > 1)
                    <x-chief-table::button
                        variant="primary"
                        type="button"
                        x-on:click.stop="
                            close()
                            $wire.isolateAsset()
                        "
                    >
                        Ontkoppel en bewerk apart in {{ $currentOwner['label'] }}
                    </x-chief-table::button>
                @endif
            </x-chief::dialog.modal.footer>
        </x-slot>
    </x-chief::dialog.modal>
@endteleport
