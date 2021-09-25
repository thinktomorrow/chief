<x-chief-formgroup label="Link" isRequired>
    <radio-options inline-template :errors="errors" default-type="{{ old('type', $menuitem->type) }}">
        <div class="space-y-3">
            {{-- Internal type --}}
            <div class="space-y-2">
                <label for="typeInternal" class="with-radio">
                    <input
                        id="typeInternal"
                        name="type"
                        type="radio"
                        value="internal"
                        v-on:click="changeType('internal')"
                        {{ (old('type', $menuitem->type) == 'internal') ? 'checked="checked"' : null }}
                    >

                    <span>Kies een interne pagina</span>
                </label>

                <div v-if="type == 'internal'">
                    <x-chief-formgroup name="owner_reference">
                        <chief-multiselect
                            name="owner_reference"
                            :options='@json($pages)'
                            selected='@json(old('owner_reference', $ownerReference))'
                            grouplabel="group"
                            groupvalues="values"
                            labelkey="label"
                            valuekey="id"
                        ></chief-multiselect>
                    </x-chief-formgroup>
                </div>
            </div>

            {{-- Custom type --}}
            <div class="space-y-2">
                <label for="typeCustom" class="with-radio">
                    <input
                        id="typeCustom"
                        name="type"
                        type="radio"
                        value="custom"
                        v-on:click="changeType('custom')"
                        {{ (old('type', $menuitem->type) == 'custom') ? 'checked="checked"' : null }}
                    >

                    <span>Kies een eigen link</span>
                </label>

                {{-- TODO: validation needs to be checked because it looks like this field can be empty? --}}
                <div v-if="type == 'custom'">
                    @if(count(config('chief.locales')) > 1)
                        <tabs v-cloak>
                            @foreach(config('chief.locales') as $locale)
                                <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.label')}">
                                    <div class="space-y-2">
                                        <input
                                            id="trans-{{ $locale }}-url"
                                            name="trans[{{ $locale }}][url]"
                                            type="text"
                                            value="{{ old('trans.'.$locale.'.url', $menuitem->dynamic('url', $locale)) }}"
                                            placeholder="e.g. https://google.com"
                                        >

                                        @error('trans' . $locale . 'url')
                                            <x-chief-inline-notification type="error">
                                                {{ $message}}
                                            </x-chief-inline-notification>
                                        @enderror
                                    </div>
                                </tab>
                            @endforeach
                        </tabs>
                    @else
                        @foreach(config('chief.locales') as $locale)
                            <div class="space-y-2">
                                <input
                                    id="trans-{{ $locale }}-url"
                                    name="trans[{{ $locale }}][url]"
                                    type="text"
                                    value="{{ old('trans.'.$locale.'.url', $menuitem->dynamic('url', $locale)) }}"
                                    placeholder="e.g. https://google.com"
                                >

                                @error('trans' . $locale . 'url')
                                    <x-chief-inline-notification type="error">
                                        {{ $message}}
                                    </x-chief-inline-notification>
                                @enderror
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- no link -->
            <label for="typeNolink" class="with-radio">
                <input
                    id="typeNolink"
                    name="type"
                    type="radio"
                    value="nolink"
                    v-on:click="changeType('nolink')"
                    {{ (old('type', $menuitem->type) == 'nolink') ? 'checked="checked"' : '' }}
                >

                <span>Geen link toevoegen aan dit menu item</span>
            </label>
        </div>
    </radio-options>
</x-chief-formgroup>
