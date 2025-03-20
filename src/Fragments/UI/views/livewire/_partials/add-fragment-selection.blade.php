<x-slot name="header">
    <x-chief::dialog.drawer.header title="Voeg een fragment toe" />
</x-slot>

<x-chief::tabs
    size="base"
    wire:key="add-fragment-tabs-{{ Str::random() }}"
    active-tab="{{ $this->showExisting() ? 'existing' : 'new' }}"
>
    <x-chief::tabs.tab wire:key="add-fragment-tab-new-{{ Str::random() }}" tab-id="new" tab-label="Nieuw">
        @include('chief-fragments::livewire._partials.add-fragment-new')
    </x-chief::tabs.tab>

    <x-chief::tabs.tab wire:key="add-fragment-tab-existing-{{ Str::random() }}" tab-id="existing" tab-label="Bestaande">
        @include('chief-fragments::livewire._partials.add-fragment-existing')
    </x-chief::tabs.tab>
</x-chief::tabs>

<x-slot name="footer">
    <x-chief::dialog.modal.footer>
        <x-chief::button wire:click="close" type="button">Annuleer</x-chief::button>
    </x-chief::dialog.modal.footer>
</x-slot>
