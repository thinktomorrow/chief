<x-chief::input.group rule="type" inner-class="space-y-2" x-data="{ type: '{{ old('type', $menuitem->type) }}' }">
    <x-chief::input.label required>Link</x-chief::input.label>

    <div class="space-y-3">
        {{-- Option: internal link --}}
        <div class="space-y-2">
            <div class="flex items-start gap-2">
                <x-chief::input.radio
                    id="type-internal"
                    name="type"
                    value="internal"
                    :checked="old('type', $menuitem->type) == 'internal'"
                    x-on:click="type = 'internal'"
                />

                <x-chief::input.label for="type-internal" unset class="body body-dark leading-5">
                    Kies een interne pagina
                </x-chief::input.label>
            </div>

            <div x-cloak x-show="type == 'internal'">
                <x-chief::input.group rule="owner_reference">
                    <x-chief::multiselect
                        name="owner_reference"
                        :options="\Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions::toMultiSelectPairs($pages)"
                        :selection="old('owner_reference', $ownerReference)"
                    />
                </x-chief::input.group>
            </div>
        </div>

        {{-- Option: custom link --}}
        <div class="space-y-2">
            <div class="flex items-start gap-2">
                <x-chief::input.radio
                    id="type-custom"
                    name="type"
                    value="custom"
                    :checked="old('type', $menuitem->type) == 'custom'"
                    x-on:click="type = 'custom'"
                />

                <x-chief::input.label for="type-custom" unset class="body body-dark leading-5">
                    Kies een eigen link
                </x-chief::input.label>
            </div>

            <div x-cloak x-show="type == 'custom'">
                @if (count(config('chief.locales')) > 1)
                    <x-chief::tabs :listen-for-external-tab="true">
                        @foreach (config('chief.locales') as $locale)
                            <x-chief::tabs.tab tab-id="{{ $locale }}">
                                <x-chief::input.group :rule="'trans' . $locale . 'url'">
                                    <x-chief::input.text
                                        id="trans-{{ $locale }}-url"
                                        name="trans[{{ $locale }}][url]"
                                        value="{{ old('trans.'.$locale.'.url', $menuitem->dynamic('url', $locale)) }}"
                                        placeholder="e.g. https://google.com"
                                    />
                                </x-chief::input.group>
                            </x-chief::tabs.tab>
                        @endforeach
                    </x-chief::tabs>
                @else
                    @foreach (config('chief.locales') as $locale)
                        <x-chief::input.group :rule="'trans' . $locale . 'url'">
                            <x-chief::input.text
                                id="trans-{{ $locale }}-url"
                                name="trans[{{ $locale }}][url]"
                                value="{{ old('trans.'.$locale.'.url', $menuitem->dynamic('url', $locale)) }}"
                                placeholder="e.g. https://google.com"
                            />
                        </x-chief::input.group>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Option: no link --}}
        <div class="flex items-start gap-2">
            <x-chief::input.radio
                id="type-nolink"
                name="type"
                value="nolink"
                :checked="old('type', $menuitem->type) == 'nolink'"
                x-on:click="type = 'nolink'"
            />

            <x-chief::input.label for="type-nolink" unset class="body body-dark leading-5">
                Geen link toevoegen aan dit menu item
            </x-chief::input.label>
        </div>
    </div>
</x-chief::input.group>
