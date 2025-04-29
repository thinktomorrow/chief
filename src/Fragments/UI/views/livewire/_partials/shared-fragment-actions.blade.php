@php
    use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
    use Thinktomorrow\Chief\Fragments\Fragment;

    $ownerCount = $fragment->sharedFragmentDtos->count();
@endphp

@if ($fragment->isShared)
    <template x-teleport="body">
        <x-chief::dialog.modal
            size="sm"
            id="shared-fragment-modal-{{ $this->getId() }}"
            title="Dit fragment wordt gebruikt op {{ $ownerCount == 1 ? ' één plaats' : $ownerCount . ' plaatsen' }}"
        >
            <p class="body text-grey-500">
                Het aanpassen of vervangen van dit fragment is van toepassing op volgende
                {{ $ownerCount == 1 ? 'plaats' : 'plaatsen' }}:
            </p>

            <div class="mt-2 overflow-hidden rounded-md border border-grey-100">
                <div class="max-h-48 divide-y divide-grey-100 overflow-y-auto">
                    @foreach ($fragment->sharedFragmentDtos as $sharedFragmentDto)
                        <div class="flex items-start justify-between gap-3 px-3 py-2.5">
                            <div class="body-dark body leading-6">
                                {{ $sharedFragmentDto->ownerLabel }} {{ $sharedFragmentDto->contextLabel ? ' > ' . $sharedFragmentDto->contextLabel : null }}
                            </div>

                            <x-chief::link
                                href="{{ $sharedFragmentDto->ownerAdminUrl }}"
                                title="{{ $sharedFragmentDto->ownerLabel }}"
                                target="_blank"
                                rel="noopener"
                            >
                                <x-chief::icon.link-square />
                            </x-chief::link>
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="body mt-4 text-grey-500">
                Wil je aanpassingen enkel op deze plaats doorvoeren? Kies dan om het fragment te ontkoppelen en apart te
                bewerken.
            </p>

            <x-slot name="footer">
                <x-chief::dialog.modal.footer>
                    <x-chief::button x-on:click.stop="close" type="button">Sluit</x-chief::button>

                    <x-chief::button x-on:click="$wire.isolateFragment(); close();" variant="orange">
                        Ontkoppel en bewerk dit fragment apart
                    </x-chief::button>
                </x-chief::dialog.modal.footer>
            </x-slot>
        </x-chief::dialog.modal>
    </template>

    <x-chief::callout data-slot="form-group" variant="blue" title="Gedeeld fragment">
        <x-slot name="icon">
            <x-chief::icon.solid.information-diamond />
        </x-slot>

        <p>
            Dit fragment is gekoppeld op {{ $ownerCount }} plaatsen. Als je aanpassingen doet aan dit fragment, worden
            deze op alle gekoppelde plaatsen doorgevoerd.

            <x-chief::link
                x-on:click="$dispatch('open-dialog', { 'id': 'shared-fragment-modal-{{ $this->getId() }}' })"
                class="underline"
            >
                Bekijk koppelingen
            </x-chief::link>
        </p>
    </x-chief::callout>
@endif
