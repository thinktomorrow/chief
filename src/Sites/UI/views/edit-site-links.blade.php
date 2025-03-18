<x-chief::dialog.modal wired>
    @if ($isOpen)

        <x-slot name="title">
            @if($addingSites)
                Voeg links toe
            @else
                Bewerk links
            @endif
        </x-slot>

        <x-slot name="subtitle">

        </x-slot>

        @if($addingSites)
            @include('chief-sites::_partials.adding-sites')
        @else
            <div class="space-y-4">
                <div>
                    @include('chief-sites::_partials.edit-site-link-items')

                    @if(count($siteLinks) < \Thinktomorrow\Chief\Sites\ChiefSites::all()->count())
                        <x-chief-table::button wire:click="addSites" variant="blue" class="shrink-0">
                            Voeg site toe
                        </x-chief-table::button>
                    @endif
                </div>
            </div>

            <x-slot name="footer">
                <x-chief-table::button wire:click="close" class="shrink-0">Annuleer</x-chief-table::button>
                <x-chief-table::button wire:click="save" variant="blue" class="shrink-0">
                    Bewaren
                </x-chief-table::button>
            </x-slot>
        @endif

    @endif
</x-chief::dialog.modal>
