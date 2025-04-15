<x-chief::form.fieldset rule="form.locales">
    <x-chief::form.label>Voor welke site wil je content toevoegen?</x-chief::form.label>

    <div class="pt-3 divide-y divide-grey-200">
        @foreach($this->getAvailableLocales() as $locale => $name)

            <div class="py-3">

                <div class="flex justify-between gap-2">
                    <div class="flex items-start gap-2">
                        <x-chief::form.input.checkbox
                            :disabled="in_array($locale, $form['active_sites'])"
                            id="item-locales-{{ $locale }}"
                            wire:key="item-locales-{{ $locale }}"
                            wire:model.change="form.locales"
                            value="{{ $locale }}"
                        />

                        <x-chief::form.label for="item-locales-{{ $locale }}" class="body-dark body leading-5" unset>
                            <strong>{{ $name }}</strong>
                            - {{ \Thinktomorrow\Chief\Sites\ChiefSites::all()->find($locale)->url }}
                        </x-chief::form.label>
                    </div>

                    @if($this->getItem()->exists() && in_array($locale, $form['locales']) || in_array($locale, $form['active_sites']))
                        <div class="flex items-start justify-end gap-2">
                            @if(in_array($locale, $this->getItem()->getActiveSites()))
                                <span class="text-xs text-right">
                                    <strong>Deze versie wordt momenteel live getoond.</strong><br>Om dit te wijzigen zet je een andere
                                fragmenten tab live voor deze taal
                                </span>
                            @else
                                <x-chief::form.input.checkbox
                                    :disabled="!in_array($locale, $form['locales'])"
                                    id="item-active-sites-{{ $locale }}"
                                    wire:key="item-active-sites-{{ $locale }}"
                                    wire:model="form.active_sites"
                                    value="{{ $locale }}"
                                />
                                <x-chief::form.label for="item-active-sites-{{ $locale }}"
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
                                        <p class="text-base/5 font-medium text-grey-700">Live zetten</p>

                                        <p class="text-sm text-grey-500">
                                            Wanneer je deze tab live zet, wordt ze onmiddellijk getoond op
                                            de {{ $name }} site. De tab die momenteel live staat, komt dan offline te
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
