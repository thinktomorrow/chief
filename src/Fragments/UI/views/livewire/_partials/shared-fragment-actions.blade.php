@php
    use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
    use Thinktomorrow\Chief\Fragments\Fragment;

    $ownerCount = $fragment->sharedFragmentDtos->count();
@endphp

{{--
    @php
    $otherOwners = collect(app(GetOwners::class)->getSharedFragmentDtos($model->getFragmentModel()))->reject(function (
    $otherOwner,
    ) use ($owner) {
    return $otherOwner['model']->modelReference()->equals($owner->modelReference());
    });
    @endphp
--}}

@if ($fragment->isShared)
    <div class="mb-4 flex items-start gap-1.5 rounded-xl bg-primary-50 p-2.5">
        <svg class="h-5 w-5 shrink-0 text-primary-500"><use xlink:href="#icon-information-circle"></use></svg>

        <p class="body text-sm text-primary-500">
            Dit fragment is gekoppeld op {{ $ownerCount }} plaatsen. Als je aanpassingen doet aan dit fragment, worden
            deze op alle gekoppelde plaatsen doorgevoerd.

            <span
                class="cursor-pointer underline"
                x-on:click="$dispatch('open-dialog', { 'id': 'shared-fragment-modal-{{ $this->getId() }}' })"
            >
                Bekijk koppelingen
            </span>
        </p>
    </div>

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
                                {{ $sharedFragmentDto->ownerLabel }}
                            </div>

                            <a
                                href="{{ $sharedFragmentDto->ownerAdminUrl }}"
                                title="{{ $sharedFragmentDto->ownerLabel }}"
                                target="_blank"
                                rel="noopener"
                            >
                                <x-chief::link>
                                    <svg><use xlink:href="#icon-external-link"></use></svg>
                                </x-chief::link>
                            </a>
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
                    <x-chief-table::button x-on:click.stop="close" type="button">Sluit</x-chief-table::button>

                    <x-chief-table::button x-on:click="$wire.isolateFragment(); close();" variant="orange">
                        Ontkoppel en bewerk dit fragment apart
                    </x-chief-table::button>
                </x-chief::dialog.modal.footer>
            </x-slot>
        </x-chief::dialog.modal>
    </template>
@endif
