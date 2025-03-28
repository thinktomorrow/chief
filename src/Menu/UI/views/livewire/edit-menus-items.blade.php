@php
    $menu = $this->menus->first();
@endphp

<div wire:key="menu-{{ $menu->id }}">
    {{-- TODO: show if creating second menu --}}
    <x-chief::callout data-slot="form-group" variant="blue" title="Wat is een menuopbouw?">
        <x-slot name="icon">
            <x-chief::icon.solid.information-diamond />
        </x-slot>

        <div class="space-y-2">
            <p>
                Een menuopbouw bepaalt hoe de structuur van je menu eruitziet. Het definieert de manier waarop content
                wordt weergegeven op verschillende sites.
            </p>

            <p>In een menuopbouw kun je:</p>

            <ul class="">
                <li>De volgorde van secties bepalen</li>
                <li>Vaste elementen zoals headers en footers instellen</li>
                <li>Specifieke layouts toewijzen aan verschillende menus</li>
                <li>Content zones definiëren waar fragmenten kunnen worden geplaatst</li>
            </ul>
        </div>
    </x-chief::callout>

    <x-chief::form.fieldset rule="title">
        <x-chief::form.label for="title">Interne titel</x-chief::form.label>
        <x-chief::form.input.text id="title" wire:model="form.{{ $menu->id }}.title" />
    </x-chief::form.fieldset>

    <x-chief::form.fieldset rule="locales">
        <x-chief::form.label for="locales">In welke talen wens je de menu items te voorzien</x-chief::form.label>
        <x-chief::multiselect
            wire:model="form.{{ $menu->id }}.locales"
            :multiple="true"
            :options="$this->getAvailableLocales()"
            :selection="old('locales', $menu->locales)"
        />
    </x-chief::form.fieldset>

    {{-- TODO: show if editing existing menu (but not if only menu left) --}}
    <x-chief::callout data-slot="form-group" variant="red" title="Menuopbouw verwijderen">
        <x-slot name="icon">
            <x-chief::icon.solid.alert />
        </x-slot>

        <div class="space-y-2">
            <p>
                Als je deze menuopbouw verwijdert, moet je een andere opbouw koppelen aan de volgende sites:
                eduplay.be/fr.
            </p>

            <p>Daarnaast worden ook alle fragmenten die enkel gebruikt worden in deze menuopbouw, verwijderd.</p>

            <div>
                <x-chief::button variant="outline-red" x-on:click="$wire.deletemenu('{{ $menu->id  }}')">
                    <x-chief::icon.delete />
                    <span>Verwijder menuopbouw</span>
                </x-chief::button>
            </div>
        </div>
    </x-chief::callout>
</div>
