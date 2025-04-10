<x-chief::form.fieldset rule="form.locales">
    <x-chief::form.label>Op welke sites wil je deze fragmenten gaan gebruiken?</x-chief::form.label>

    <div class="pt-3 divide-y divide-grey-200">
        @foreach($this->getAvailableLocales() as $locale => $name)

            <div class="py-3">

                <div class="flex justify-between gap-2">
                    <div class="flex items-start gap-2">
                        <x-chief::form.input.checkbox
                            :disabled="in_array($locale, $form['active_sites'])"
                            id="context-locales-{{ $locale }}"
                            wire:key="context-locales-{{ $locale }}"
                            wire:model.change="form.locales"
                            value="{{ $locale }}"
                        />

                        <x-chief::form.label for="context-locales-{{ $locale }}" class="body-dark body leading-5" unset>
                            {{ $name }}
                        </x-chief::form.label>
                    </div>

                    @if(in_array($locale, $form['locales']) || in_array($locale, $form['active_sites']))
                        <div class="flex items-start justify-end gap-2">
                            @if(in_array($locale, $context->activeSites))
                                <span class="text-xs text-right">
                                    <strong>Deze versie wordt momenteel live getoond.</strong><br>Om dit te wijzigen zet je een andere
                                fragmenten tab live voor deze taal
                                </span>
                            @else
                                <x-chief::form.input.checkbox
                                    :disabled="!in_array($locale, $form['locales'])"
                                    id="context-active-sites-{{ $locale }}"
                                    wire:key="context-active-sites-{{ $locale }}"
                                    wire:model="form.active_sites"
                                    value="{{ $locale }}"
                                />
                                <x-chief::form.label for="context-active-sites-{{ $locale }}"
                                                     class="body-dark body text-sm leading-5 flex justify-start items-center"
                                                     unset>
                                    Zet deze versie live
                                    <x-chief::button
                                        type="button"
                                        size="xs"
                                        variant="transparent"
                                        tabindex="-1"
                                        x-on:click="$dispatch('open-dialog', { 'id': 'activate-site-info-{{ $locale }}' })"
                                    >
                                        <x-chief::icon.information-circle />
                                    </x-chief::button>
                                </x-chief::form.label>

                                <x-chief::dialog.dropdown
                                    id="activate-site-info-{{ $locale }}"
                                    :offset="4"
                                    placement="bottom-center"
                                >
                                    <div class="max-w-sm space-y-2 px-3 py-1.5">
                                        <p class="text-base/5 font-medium text-grey-700">Fragmenten live zetten</p>

                                        <p class="text-sm text-grey-500">
                                            Wanneer je deze fragmenten live zet, worden ze onmiddellijk getoond op
                                            de {{ $name }} site. De vorige selectie van fragmenten komt dan offline te
                                            staan.
                                        </p>
                                    </div>
                                </x-chief::dialog.dropdown>
                            @endif
                        </div>
                    @endif
                </div>

            </div>

        @endforeach
    </div>
</x-chief::form.fieldset>
