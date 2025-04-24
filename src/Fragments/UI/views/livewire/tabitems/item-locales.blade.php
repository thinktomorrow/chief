<x-chief::form.fieldset rule="form.locales">
    <x-chief::form.label>Actieve sites voor deze versie</x-chief::form.label>

    <div data-slot="control" class="divide-y divide-grey-200 rounded-lg border border-grey-200">
        @foreach ($this->getAvailableLocales() as $locale => $name)
            <div class="flex justify-between gap-3 p-3">
                <div class="my-0.5 flex items-start gap-2">
                    <x-chief::form.input.checkbox
                        :disabled="in_array($locale, $form['active_sites'])"
                        id="item-locales-{{ $locale }}"
                        wire:key="item-locales-{{ $locale }}"
                        wire:model.change="form.locales"
                        value="{{ $locale }}"
                    />

                    <x-chief::form.label for="item-locales-{{ $locale }}" class="body leading-5 text-grey-500" unset>
                        <span class="body-dark font-medium">{{ $name }}</span>
                        - {{ \Thinktomorrow\Chief\Sites\ChiefSites::all()->find($locale)->url }}
                    </x-chief::form.label>
                </div>

                @if (($this->getItem()->exists() && in_array($locale, $form['locales'])) || in_array($locale, $form['active_sites']))
                    <div class="flex items-start justify-end gap-1">
                        @if (in_array($locale, $this->getItem()->getActiveSites()))
                            <span class="text-right text-sm text-grey-500">
                                <span class="body-dark font-medium">
                                    Deze versie wordt gebruikt door de {{ $name }} site.
                                </span>
                                Om dit te wijzigen wijs je een andere versie toe aan de {{ $name }} site.
                            </span>
                        @elseif (! in_array($locale, $form['active_sites']))
                            <x-chief::button
                                type="button"
                                size="xs"
                                variant="outline-white"
                                tabindex="-1"
                                x-on:click="$wire.addActiveSite('{{ $locale }}')"
                            >
                                Gebruik deze versie voor de {{ $name }} site
                            </x-chief::button>
                        @else
                            <div class="flex items-start gap-1">
                                <span class="text-sm leading-6 text-grey-500">
                                    Deze versie zal gebruikt worden op de {{ $name }} site.
                                </span>

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
        @endforeach
    </div>
</x-chief::form.fieldset>
