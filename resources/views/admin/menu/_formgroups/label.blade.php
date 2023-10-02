@php use Thinktomorrow\Chief\Resource\Locale\ChiefLocaleConfig; @endphp
<x-chief::input.group>
    <x-chief::input.label required>
        Label
    </x-chief::input.label>

    <x-chief::input.description>
        Dit is de tekst die wordt getoond in het menu. Kies een korte, duidelijke term.
    </x-chief::input.description>

    @if(count(ChiefLocaleConfig::getLocales()) > 1)
        <x-chief::tabs :listen-for-external-tab="true">
            @foreach(ChiefLocaleConfig::getLocales() as $locale)
                <x-chief::tabs.tab tab-id='{{ $locale }}'>
                    <x-chief::input.group :rule="'trans.' . $locale . '.label'">
                        <x-chief::input.text
                                name="trans[{{ $locale }}][label]"
                                id="trans-{{ $locale }}-label"
                                placeholder="Menu label"
                                value="{{ old('trans.'.$locale.'.label', $menuitem->dynamic('label', $locale)) }}"
                        />
                    </x-chief::input.group>
                </x-chief::tabs.tab>
            @endforeach
        </x-chief::tabs>
    @else
        @foreach(ChiefLocaleConfig::getLocales() as $locale)
            <x-chief::input.group :rule="'trans.' . $locale . '.label'">
                <x-chief::input.text
                        name="trans[{{ $locale }}][label]"
                        id="trans-{{ $locale }}-label"
                        placeholder="Menu label"
                        value="{{ old('trans.'.$locale.'.label', $menuitem->dynamic('label', $locale)) }}"
                />
            </x-chief::input.group>
        @endforeach
    @endif
</x-chief::input.group>
