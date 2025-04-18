<x-chief::form.fieldset rule="form.locales">
    <x-chief::form.label>Voor welke site wil je deze versie opmaken?</x-chief::form.label>

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
                                    <strong>Deze versie wordt gebruikt door de {{ $name }} site.</strong><br>Om dit te wijzigen wijs je een andere
                                versie toe aan de {{ $name }} site.
                                </span>
                            @elseif(!in_array($locale, $form['active_sites']))

                                <x-chief::button
                                    type="button"
                                    size="xs"
                                    variant="blue"
                                    tabindex="-1"
                                    x-on:click="$wire.addActiveSite('{{ $locale }}')"
                                >
                                    Gebruik deze versie voor de {{ $name }} site
                                </x-chief::button>
                                <x-chief::button
                                    type="button"
                                    size="xs"
                                    variant="transparent"
                                    tabindex="-1"
                                    x-on:click="$dispatch('open-dialog', { 'id': 'activate-site-info-{{ $locale }}' })"
                                >
                                    <x-chief::icon.information-circle />
                                </x-chief::button>

                                <x-chief::dialog.dropdown
                                    id="activate-site-info-{{ $locale }}"
                                    :offset="4"
                                    placement="bottom-center"
                                >
                                    <div class="max-w-sm space-y-2 px-3 py-1.5">
                                        <p class="text-base/5 font-medium text-grey-700">Live zetten</p>

                                        <p class="text-sm text-grey-500">
                                            Wanneer je deze versie live zet, wordt ze onmiddellijk getoond op
                                            de {{ $name }} site. De versie die momenteel live staat, komt dan offline te
                                            staan.
                                        </p>
                                    </div>
                                </x-chief::dialog.dropdown>
                            @else
                                <div class="flex">
                                    <span class="text-sm text-grey-500">Deze versie wordt de versie voor de {{ $name }} site.</span>
                                    <x-chief::button
                                        type="button"
                                        size="xs"
                                        variant="grey"
                                        tabindex="-1"
                                        x-on:click="$wire.removeActiveSite('{{ $locale }}')"
                                    >
                                        <x-chief::icon.arrow-turn-backward />
                                    </x-chief::button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

            </div>

        @endforeach
    </div>
</x-chief::form.fieldset>
