@php
    $context = $this->contexts->first();
@endphp

<div wire:key="context-{{ $context->id }}">
    {{-- TODO: show if creating second context --}}
    <x-chief::callout data-slot="form-group" variant="blue" title="Voeg een pagina inhoud toe">
        <x-slot name="icon">
            <x-chief::icon.solid.information-diamond />
        </x-slot>

        <div class="space-y-2">
            <p>
                Met een extra inhoud kan je de structuur van je pagina aanpassen per site.
            </p>

            <p>Met een extra pagina inhoud kan je:</p>

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


</div>
