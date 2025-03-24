@php
    use Thinktomorrow\Chief\Sites\ChiefSites;
@endphp

<x-chief::form.fieldset>
    <x-chief::form.label required>Label</x-chief::form.label>

    <x-chief::form.description>
        Dit is de tekst die wordt getoond in het menu. Kies een korte, duidelijke term.
    </x-chief::form.description>

    @if (count(\Thinktomorrow\Chief\Sites\Locales\ChiefLocales::locales()) > 1)
        <x-chief::tabs :listen-for-external-tab="true">
            @foreach (\Thinktomorrow\Chief\Sites\Locales\ChiefLocales::locales() as $locale)
                <x-chief::tabs.tab tab-id="{{ $locale }}">
                    <x-chief::form.fieldset :rule="'trans.' . $locale . '.label'">
                        <x-chief::input.text
                            name="trans[{{ $locale }}][label]"
                            id="trans-{{ $locale }}-label"
                            placeholder="Menu label"
                            value="{{ old('trans.'.$locale.'.label', $menuitem->dynamic('label', $locale)) }}"
                        />
                    </x-chief::form.fieldset>
                </x-chief::tabs.tab>
            @endforeach
        </x-chief::tabs>
    @else
        @foreach (\Thinktomorrow\Chief\Sites\Locales\ChiefLocales::locales() as $locale)
            <x-chief::form.fieldset :rule="'trans.' . $locale . '.label'">
                <x-chief::input.text
                    name="trans[{{ $locale }}][label]"
                    id="trans-{{ $locale }}-label"
                    placeholder="Menu label"
                    value="{{ old('trans.'.$locale.'.label', $menuitem->dynamic('label', $locale)) }}"
                />
            </x-chief::form.fieldset>
        @endforeach
    @endif
</x-chief::form.fieldset>
