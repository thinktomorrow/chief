<x-chief::form.fieldset rule="type" x-data="{ type: '{{ old('type', $menuitem->type) }}' }">
    <x-chief::form.label required>Link</x-chief::form.label>

    <div data-slot="control" class="space-y-2">
        {{-- Option: internal link --}}
        <div class="space-y-1">
            <div class="flex items-start gap-2">
                <x-chief::form.input.radio
                    id="type-internal"
                    name="type"
                    value="internal"
                    :checked="old('type', $menuitem->type) == 'internal'"
                    x-on:click="type = 'internal'"
                />

                <x-chief::form.label for="type-internal" unset class="body body-dark leading-5">
                    Kies een interne pagina
                </x-chief::form.label>
            </div>

            <div x-cloak x-show="type == 'internal'">
                <x-chief::form.fieldset rule="owner_reference">
                    <x-chief::multiselect
                        name="owner_reference"
                        :options="\Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions::toMultiSelectPairs($pages)"
                        :selection="old('owner_reference', $ownerReference)"
                    />
                </x-chief::form.fieldset>
            </div>
        </div>

        {{-- Option: custom link --}}
        <div class="space-y-2">
            <div class="flex items-start gap-2">
                <x-chief::form.input.radio
                    id="type-custom"
                    name="type"
                    value="custom"
                    :checked="old('type', $menuitem->type) == 'custom'"
                    x-on:click="type = 'custom'"
                />

                <x-chief::form.label for="type-custom" unset class="body body-dark leading-5">
                    Kies een eigen link
                </x-chief::form.label>
            </div>

            <div x-cloak x-show="type == 'custom'">
                @if (count(\Thinktomorrow\Chief\Sites\Locales\ChiefLocales::locales()) > 1)
                    <x-chief::tabs :should-listen-for-external-tab="true">
                        @foreach (\Thinktomorrow\Chief\Sites\Locales\ChiefLocales::locales() as $locale)
                            <x-chief::tabs.tab tab-id="{{ $locale }}">
                                <x-chief::form.fieldset :rule="'trans' . $locale . 'url'">
                                    <x-chief::form.input.text
                                        id="trans-url-{{ $locale }}"
                                        name="trans[url][{{ $locale }}]"
                                        value="{{ old('trans.url.'.$locale, $menuitem->dynamic('url', $locale)) }}"
                                        placeholder="e.g. https://google.com"
                                    />
                                </x-chief::form.fieldset>
                            </x-chief::tabs.tab>
                        @endforeach
                    </x-chief::tabs>
                @else
                    @foreach (\Thinktomorrow\Chief\Sites\Locales\ChiefLocales::locales() as $locale)
                        <x-chief::form.fieldset :rule="'trans' . $locale . 'url'">
                            <x-chief::form.input.text
                                id="trans-url-{{ $locale }}"
                                name="trans[url][{{ $locale }}]"
                                value="{{ old('trans.url.'.$locale, $menuitem->dynamic('url', $locale)) }}"
                                placeholder="e.g. https://google.com"
                            />
                        </x-chief::form.fieldset>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Option: no link --}}
        <div class="flex items-start gap-2">
            <x-chief::form.input.radio
                id="type-nolink"
                name="type"
                value="nolink"
                :checked="old('type', $menuitem->type) == 'nolink'"
                x-on:click="type = 'nolink'"
            />

            <x-chief::form.label for="type-nolink" unset class="body body-dark leading-5">
                Geen link toevoegen aan dit menu item
            </x-chief::form.label>
        </div>
    </div>
</x-chief::form.fieldset>
