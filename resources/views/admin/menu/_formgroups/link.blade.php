<x-chief::input.group inner-class="space-y-2">
    <x-chief::input.label required>
        Link
    </x-chief::input.label>

    <radio-options inline-template :errors="errors" default-type="{{ old('type', $menuitem->type) }}">
        <div class="space-y-3">
            {{-- Option: internal link --}}
            <div class="space-y-2">
                <label for="typeInternal" class="flex items-start gap-2">
                    <input
                        type="radio"
                        id="typeInternal"
                        name="type"
                        value="internal"
                        v-on:click="changeType('internal')"
                        {{ (old('type', $menuitem->type) == 'internal') ? 'checked="checked"' : null }}
                        class="form-input-radio"
                    >

                    <span class="body-dark">Kies een interne pagina</span>
                </label>

                <div v-if="type == 'internal'">
                    <x-chief::input.group rule="owner_reference">
                        <chief-multiselect
                            name="owner_reference"
                            :options='@json($pages)'
                            selected='@json(old('owner_reference', $ownerReference))'
                            grouplabel="group"
                            groupvalues="values"
                            labelkey="label"
                            valuekey="id"
                        />
                    </x-chief::input.group>
                </div>
            </div>

            {{-- Option: custom link --}}
            <div class="space-y-2">
                <label for="typeCustom" class="flex items-start gap-2">
                    <input
                        type="radio"
                        id="typeCustom"
                        name="type"
                        value="custom"
                        v-on:click="changeType('custom')"
                        {{ (old('type', $menuitem->type) == 'custom') ? 'checked="checked"' : null }}
                        class="form-input-radio"
                    >

                    <span class="body-dark">Kies een eigen link</span>
                </label>

                <div v-if="type == 'custom'">
                    @if(count(config('chief.locales')) > 1)
                        <tabs v-cloak>
                            @foreach(config('chief.locales') as $locale)
                                <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.label')}">
                                    <x-chief::input.group :rule="'trans' . $locale . 'url'">
                                        <x-chief::input.text
                                            id="trans-{{ $locale }}-url"
                                            name="trans[{{ $locale }}][url]"
                                            value="{{ old('trans.'.$locale.'.url', $menuitem->dynamic('url', $locale)) }}"
                                            placeholder="e.g. https://google.com"
                                        />
                                    </x-chief::input.group>
                                </tab>
                            @endforeach
                        </tabs>
                    @else
                        @foreach(config('chief.locales') as $locale)
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
            <label for="typeNolink" class="flex items-start gap-2">
                <input
                    id="typeNolink"
                    name="type"
                    type="radio"
                    value="nolink"
                    v-on:click="changeType('nolink')"
                    {{ (old('type', $menuitem->type) == 'nolink') ? 'checked="checked"' : '' }}
                    class="form-input-radio"
                >

                <span class="body-dark">Geen link toevoegen aan dit menu item</span>
            </label>
        </div>
    </radio-options>
</x-chief::input.group>
