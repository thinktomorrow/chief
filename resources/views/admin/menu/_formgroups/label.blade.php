@php use Thinktomorrow\Chief\Sites\ChiefSites; @endphp
<x-chief::input.group>
    <x-chief::input.label required>
        Label
    </x-chief::input.label>

    <x-chief::input.description>
        Dit is de tekst die wordt getoond in het menu. Kies een korte, duidelijke term.
    </x-chief::input.description>

    @if(count(ChiefLocales::fieldLocales()) > 1)
        <x-chief::tabs :listen-for-external-tab="true">
            @foreach(ChiefLocales::fieldLocales() as $locale)
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
        @foreach(ChiefLocales::fieldLocales() as $locale)
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
