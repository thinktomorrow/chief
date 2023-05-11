<x-chief::input.group>
    <x-chief::input.label required>
        Label
    </x-chief::input.label>

    <x-chief::input.description>
        Dit is de tekst die wordt getoond in het menu. Kies een korte, duidelijke term.
    </x-chief::input.description>

    @if(count(config('chief.locales')) > 1)
        <tabs v-cloak>
            @foreach(config('chief.locales') as $locale)
                <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}')}">
                    <x-chief::input.group :rule="'trans.' . $locale . '.label'">
                        <x-chief::input.text
                            name="trans[{{ $locale }}][label]"
                            id="trans-{{ $locale }}-label"
                            placeholder="Menu label"
                            value="{{ old('trans.'.$locale.'.label', $menuitem->dynamic('label', $locale)) }}"
                        />
                    </x-chief::input.group>
                </tab>
            @endforeach
        </tabs>
    @else
        @foreach(config('chief.locales') as $locale)
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
