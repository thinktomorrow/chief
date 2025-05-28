@php
    @endphp

<x-chief::form.fieldset>
    <x-chief::form.label required>Label</x-chief::form.label>

    <x-chief::form.description>
        Dit is de tekst die wordt getoond in het menu. Kies een korte, duidelijke term.
    </x-chief::form.description>

    @if (count(\Thinktomorrow\Chief\Sites\ChiefSites::locales()) > 1)
        <x-chief::tabs :should-listen-for-external-tab="true">
            @foreach (\Thinktomorrow\Chief\Sites\ChiefSites::locales() as $locale)
                <x-chief::tabs.tab tab-id="{{ $locale }}"
                                   tab-label="{{ \Thinktomorrow\Chief\Sites\ChiefSites::all()->find($locale)->shortName }}">
                    <x-chief::form.fieldset :rule="'trans.label.'.$locale">
                        <x-chief::form.input.text
                            name="trans[label][{{ $locale }}]"
                            id="trans-label-{{ $locale }}"
                            placeholder="Menu label"
                            value="{{ old('trans.label.'.$locale, $menuitem->dynamic('label', $locale)) }}"
                        />
                    </x-chief::form.fieldset>
                </x-chief::tabs.tab>
            @endforeach
        </x-chief::tabs>
    @else
        @foreach (\Thinktomorrow\Chief\Sites\ChiefSites::locales() as $locale)
            <x-chief::form.fieldset :rule="'trans.label.' . $locale">
                <x-chief::form.input.text
                    name="trans[label][{{ $locale }}]"
                    id="trans-label-{{ $locale }}"
                    placeholder="Menu label"
                    value="{{ old('trans.label.'.$locale, $menuitem->dynamic('label', $locale)) }}"
                />
            </x-chief::form.fieldset>
        @endforeach
    @endif
</x-chief::form.fieldset>
