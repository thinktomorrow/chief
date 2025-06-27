@if ($currentOwner && $ownerCount > 1)
    <x-chief::button
        x-on:click="$dispatch('open-dialog', { 'id': 'file-owners-modal-{{ $this->getId() }}' })"
        variant="grey"
        size="sm"
    >
        Bewerk koppeling
    </x-chief::button>
@endif

@teleport('body')
<x-chief::dialog.modal
    id="file-owners-modal-{{ $this->getId() }}"
    title="Dit bestand wordt op {{ $ownerCount == 1 ? ' één plaats' : $ownerCount . ' plaatsen' }} gebruikt"
>
    <div class="mt-2 overflow-hidden rounded-md border border-grey-100">
        <div class="max-h-48 divide-y divide-grey-100 overflow-y-auto">
            @foreach ($previewFile->owners as $owner)
                <div class="flex items-start justify-between gap-3 px-3 py-2.5">
                    <div class="flex justify-between w-full body-dark body leading-6">
                        <span>{{ teaser($owner['label'], 30, '...') }}</span>
                        @if (isset($modelReference) && $owner['modelReference'] == $modelReference)
                            <x-chief::button
                                variant="blue"
                                type="button"
                                size="xs"
                                x-on:click.stop="close(); $wire.isolateAsset()">
                                <x-chief::icon.unlink />
                                <span>Afzonderlijk bewerken op deze pagina</span>
                            </x-chief::button>
                        @else
                            <x-chief::button
                                href="{{ $owner['adminUrl'] }}"
                                title="Bekijk"
                                size="xs"
                                variant="grey"
                                target="_blank"
                                rel="noopener"
                            >
                                <x-chief::icon.link-square />
                                <span>Ga naar deze pagina</span>
                            </x-chief::button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if ($currentOwner && $ownerCount > 1)
        <p class="body mt-4 text-grey-500">
            Wil je de afbeelding alleen op deze pagina aanpassen? Koppel ze dan los om ze apart te kunnen bewerken.
        </p>
    @endif

    <x-slot name="footer">
        <x-chief::dialog.modal.footer>
            <x-chief::button x-on:click="close" type="button">Sluit</x-chief::button>
        </x-chief::dialog.modal.footer>
    </x-slot>
</x-chief::dialog.modal>
@endteleport
