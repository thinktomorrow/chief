@formgroup
    @slot('label', 'Link')
    @slot('isRequired', true)

    <radio-options inline-template :errors="errors" default-type="{{ old('type', $menuitem->type) }}">
        <div class="space-y-3 prose prose-dark">
            <!-- internal type -->
            <label for="typeInternal" class="block cursor-pointer space-y-3">
                <div class="flex items-center space-x-2">
                    <input
                        id="typeInternal"
                        name="type"
                        type="radio"
                        value="internal"
                        v-on:click="changeType('internal')"
                        {{ (old('type', $menuitem->type) == 'internal') ? 'checked="checked"' : '' }}
                    >

                    <span>Kies een interne pagina</span>
                </div>

                <div v-if="type == 'internal'" class="relative space-y-3">
                    <chief-multiselect
                        name="owner_reference"
                        :options='@json($pages)'
                        selected='@json(old('owner_reference', $ownerReference))'
                        grouplabel="group"
                        groupvalues="values"
                        labelkey="label"
                        valuekey="id"
                    ></chief-multiselect>

                    @error('owner_reference')
                        <x-inline-notification type="error">
                            {{ $message}}
                        </x-inline-notification>
                    @enderror
                </div>
            </label>

            <!-- custom type -->
            <label for="typeCustom" class="block cursor-pointer space-y-3">
                <div class="flex items-center space-x-2">
                    <input
                        id="typeCustom"
                        name="type"
                        type="radio"
                        value="custom"
                        v-on:click="changeType('custom')"
                        {{ (old('type', $menuitem->type) == 'custom') ? 'checked="checked"' : '' }}
                    >

                    <span>Kies een eigen link</span>
                </div>

                {{-- TODO: validation needs to be checked because it looks like this field can be empty? --}}
                <div v-if="type == 'custom'" class="relative">
                    @if(count(config('chief.locales')) > 1)
                        <tabs v-cloak>
                            @foreach(config('chief.locales') as $locale)
                                <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.label')}">
                                    <div class="space-y-3">
                                        <input
                                            id="trans-{{ $locale }}-url"
                                            name="trans[{{ $locale }}][url]"
                                            type="text"
                                            value="{{ old('trans.'.$locale.'.url', $menuitem->dynamic('url', $locale)) }}"
                                            placeholder="e.g. https://google.com"
                                            class="input w-full"
                                        >

                                        @error('trans' . $locale . 'url')
                                            <x-inline-notification type="error">
                                                {{ $message}}
                                            </x-inline-notification>
                                        @enderror
                                    </div>
                                </tab>
                            @endforeach
                        </tabs>
                    @else
                        @foreach(config('chief.locales') as $locale)
                            <div class="space-y-3">
                                <input
                                    id="trans-{{ $locale }}-url"
                                    name="trans[{{ $locale }}][url]"
                                    type="text"
                                    value="{{ old('trans.'.$locale.'.url', $menuitem->dynamic('url', $locale)) }}"
                                    placeholder="e.g. https://google.com"
                                    class="input w-full"
                                >

                                @error('trans' . $locale . 'url')
                                    <x-inline-notification type="error">
                                        {{ $message}}
                                    </x-inline-notification>
                                @enderror
                            </div>
                        @endforeach
                    @endif
                </div>
            </label>

            <!-- no link -->
            <label for="typeNolink" class="flex items-center space-x-2 cursor-pointer">
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
@endformgroup
