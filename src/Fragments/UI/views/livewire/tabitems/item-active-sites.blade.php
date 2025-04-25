<x-chief::form.fieldset rule="form.active_sites">
    <x-chief::form.label>Op welke sites wil je deze versie tonen?</x-chief::form.label>

    <div data-slot="control" class="divide-y divide-grey-200 rounded-lg border border-grey-200">
        @foreach ($this->getAvailableLocales() as $locale)
            <div class="flex justify-between gap-3 p-3">
                @if (($this->getItem()->exists() && in_array($locale, $form['locales'])) || in_array($locale, $form['active_sites']))
                    <div class="flex items-start justify-end gap-1">
                        @if (in_array($locale, $this->getItem()->getActiveSites()))
                            <div class="flex gap-2 items-center">
                                <x-chief::icon.link class="size-4 text-grey-500" />
                                <div class="flex flex-col text-sm text-grey-500">
                                    <span class="body-dark font-medium">
                                        Deze versie wordt getoond op de {{ \Thinktomorrow\Chief\Sites\ChiefSites::adjective($locale) }} site.
                                    </span>
                                    <span>
                                        Om dit te wijzigen wijs je een andere versie toe aan de {{ \Thinktomorrow\Chief\Sites\ChiefSites::adjective($locale) }} site.
                                    </span>
                                </div>
                            </div>

                        @elseif (! in_array($locale, $form['active_sites']))
                            <x-chief::button
                                type="button"
                                size="xs"
                                variant="outline-white"
                                tabindex="-1"
                                x-on:click="$wire.addActiveSite('{{ $locale }}')"
                            >
                                Toon deze versie op de {{ \Thinktomorrow\Chief\Sites\ChiefSites::adjective($locale) }}
                                site
                            </x-chief::button>
                        @else
                            <div class="flex items-start gap-1">
                                <span class="text-sm leading-6 text-grey-500">
                                    Deze versie zal gebruikt worden op de {{ \Thinktomorrow\Chief\Sites\ChiefSites::adjective($locale) }} site.
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
