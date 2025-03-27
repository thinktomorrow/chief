@php
    $context = $this->contexts->first();
@endphp

<div wire:key="menu-{{ $context->id }}">
    {{-- TODO: show if creating second context --}}
    <x-chief::callout data-slot="form-group" variant="blue" title="Wat is een paginaopbouw?">
        <x-slot name="icon">
            <x-chief::icon.solid.information-diamond />
        </x-slot>

        <div class="space-y-2">
            <p>
                Een paginaopbouw bepaalt hoe de structuur van je pagina eruitziet. Het definieert de manier waarop
                content wordt weergegeven op verschillende sites.
            </p>

            <p>In een paginaopbouw kun je:</p>

            <ul class="">
                <li>De volgorde van secties bepalen</li>
                <li>Vaste elementen zoals headers en footers instellen</li>
                <li>Specifieke layouts toewijzen aan verschillende pagina's</li>
                <li>Content zones definiëren waar fragmenten kunnen worden geplaatst</li>
            </ul>
        </div>
    </x-chief::callout>

    <x-chief::form.fieldset rule="title">
        <x-chief::form.label for="title">Titel</x-chief::form.label>
        <x-chief::form.input.text id="title" wire:model="form.{{ $context->id  }}.title" />
    </x-chief::form.fieldset>

    <x-chief::form.fieldset rule="locales">
        <x-chief::form.label for="locales">In welke talen wens je te gebruiken op de pagina?</x-chief::form.label>

        <x-chief::multiselect
            wire:model="form.{{ $context->id  }}.locales"
            :multiple="true"
            :options="$this->getAvailableLocales()"
            :selection="old('locales', $context->locales)"
        />
    </x-chief::form.fieldset>

    {{-- TODO: show if editing existing context (but not if only context left) --}}
    <x-chief::callout data-slot="form-group" variant="red" title="Paginaopbouw verwijderen">
        <x-slot name="icon">
            <x-chief::icon.solid.alert />
        </x-slot>

        <div class="space-y-2">
            <p>
                Als je deze paginaopbouw verwijdert, moet je een andere opbouw koppelen aan de volgende sites:
                eduplay.be/fr.
            </p>

            <p>Daarnaast worden ook alle fragmenten die enkel gebruikt worden in deze paginaopbouw, verwijderd.</p>

            <div>
                <x-chief::button variant="outline-red" x-on:click="$wire.deleteContext('{{ $context->id  }}')">
                    <x-chief::icon.delete />
                    <span>Verwijder paginaopbouw</span>
                </x-chief::button>
            </div>
        </div>
    </x-chief::callout>
</div>
