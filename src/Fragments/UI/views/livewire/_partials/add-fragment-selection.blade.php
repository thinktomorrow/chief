<x-slot name="header">
    <x-chief::dialog.drawer.header title="Fragment toevoegen">
        <x-chief::tabs
            size="base"
            wire:key="add-fragment-tabs-{{ Str::random() }}"
            active-tab="{{ $this->showExisting() ? 'existing' : 'new' }}"
            :show-tabs="false"
        >
            <x-chief::tabs.tab wire:key="add-fragment-tab-new-{{ Str::random() }}" tab-id="new" tab-label="Nieuw" />
            <x-chief::tabs.tab
                wire:key="add-fragment-tab-existing-{{ Str::random() }}"
                tab-id="existing"
                tab-label="Bestaand"
            />
        </x-chief::tabs>
    </x-chief::dialog.drawer.header>
</x-slot>

<x-chief::tabs
    size="base"
    wire:key="add-fragment-tabs-{{ Str::random() }}"
    active-tab="{{ $this->showExisting() ? 'existing' : 'new' }}"
    :show-nav="false"
    :should-listen-for-external-tab="true"
>
    <x-chief::tabs.tab wire:key="add-fragment-tab-new-{{ Str::random() }}" tab-id="new">
        @include('chief-fragments::livewire._partials.add-fragment-new')
    </x-chief::tabs.tab>

    <x-chief::tabs.tab wire:key="add-fragment-tab-existing-{{ Str::random() }}" tab-id="existing">
        @include('chief-fragments::livewire._partials.add-fragment-existing')
    </x-chief::tabs.tab>
</x-chief::tabs>

<x-slot name="footer">
    <x-chief::dialog.drawer.footer>
        <x-chief::button wire:click="close" type="button">Annuleer</x-chief::button>
    </x-chief::dialog.drawer.footer>
</x-slot>
