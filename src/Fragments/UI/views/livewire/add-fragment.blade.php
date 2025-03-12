<x-chief::dialog.modal wired>
    @if ($isOpen)

        <x-slot name="title">
            @if($showCreate)
                <x-chief-table::button wire:click="$set('showCreate', false)" class="shrink-0"><</x-chief-table::button>
                Voeg een fragment toe
            @else
                Voeg een nieuw fragment toe
            @endif
        </x-slot>

        <x-slot name="subtitle">

        </x-slot>

        <div class="space-y-4">
            <div>
                @if($showCreate)
                    @include('chief-fragments::livewire._partials.add-fragment-new-form')
                @else
                    <x-chief::tabs wire:key="add-fragment-tabs-{{ Str::random() }}"
                                   active-tab="{{ $this->showExisting() ? 'existing' : 'new' }}" class="-mb-3">
                        <x-chief::tabs.tab wire:key="add-fragment-tab-new-{{ Str::random() }}" tab-id='new'
                                           tab-label="Nieuw">
                            @include('chief-fragments::livewire._partials.add-fragment-new')
                        </x-chief::tabs.tab>
                        <x-chief::tabs.tab wire:key="add-fragment-tab-existing-{{ Str::random() }}" tab-id='existing'
                                           tab-label="Bestaande">
                            @include('chief-fragments::livewire._partials.add-fragment-existing')
                        </x-chief::tabs.tab>

                    </x-chief::tabs>
                @endif
            </div>
        </div>

        <x-slot name="footer">
            @if($showCreate)
                <x-chief-table::button wire:click="$set('showCreate', false)" class="shrink-0">Annuleer
                </x-chief-table::button>
                <x-chief-table::button wire:click="save" variant="blue" class="shrink-0">
                    Bewaren
                </x-chief-table::button>
            @else
                <x-chief-table::button wire:click="close" class="shrink-0">Annuleer</x-chief-table::button>
            @endif
        </x-slot>
    @endif
</x-chief::dialog.modal>
