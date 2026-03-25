<x-slot name="header">
    <x-chief::dialog.drawer.header
        title="Fragment toevoegen"
        x-data
        x-on:chieftab.window="if ($event.detail.reference === 'add-fragment-tabs') { $wire.onTabChanged($event.detail.id) }"
    >
        <x-chief::tabs
            size="base"
            wire:key="add-fragment-tabs-header"
            reference="add-fragment-tabs"
            active-tab="{{ $this->showExisting() ? 'existing' : 'new' }}"
            :show-tabs="false"
        >
            <x-chief::tabs.tab wire:key="add-fragment-tab-header-new" tab-id="new" tab-label="Nieuw" />
            <x-chief::tabs.tab wire:key="add-fragment-tab-header-existing" tab-id="existing" tab-label="Bestaand" />
        </x-chief::tabs>
    </x-chief::dialog.drawer.header>
</x-slot>

<x-chief::tabs
    size="base"
    wire:key="add-fragment-tabs-content"
    reference="add-fragment-tabs"
    active-tab="{{ $this->showExisting() ? 'existing' : 'new' }}"
    :show-nav="false"
    :should-listen-for-external-tab="true"
>
    <x-chief::tabs.tab wire:key="add-fragment-tab-content-new" tab-id="new">
        @include('chief-fragments::livewire._partials.add-fragment-new')
    </x-chief::tabs.tab>

    <x-chief::tabs.tab wire:key="add-fragment-tab-content-existing" tab-id="existing">
        <div wire:loading wire:target="onTabChanged">
            <div class="flex justify-start gap-1.5">
                <x-chief::icon.loading class="body-dark size-5 animate-spin" />
                <p class="body body-dark leading-5">Even wachten...</p>
            </div>
        </div>

        <div wire:loading.remove wire:target="onTabChanged">
            @if ($this->shouldRenderExistingTab())
                @include('chief-fragments::livewire._partials.add-fragment-existing')
            @endif
        </div>
    </x-chief::tabs.tab>
</x-chief::tabs>

<x-slot name="footer">
    <x-chief::dialog.drawer.footer>
        <x-chief::button wire:click="close" type="button">Annuleer</x-chief::button>
    </x-chief::dialog.drawer.footer>
</x-slot>
