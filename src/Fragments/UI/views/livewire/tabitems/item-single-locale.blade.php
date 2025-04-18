<x-chief::form.fieldset rule="form.locales">
    @foreach($this->getAvailableLocales() as $locale => $name)
        <div class="flex items-start gap-2">
            <x-chief::form.input.checkbox
                :disabled="in_array($locale, $form['active_sites'])"
                id="item-active-sites-{{ $locale }}"
                wire:key="item-active-sites-{{ $locale }}"
                wire:model="form.active_sites"
                value="{{ $locale }}"
            />
            <x-chief::form.label for="item-active-sites-{{ $locale }}"
                                 class="body-dark body text-sm leading-5 flex justify-start items-center"
                                 unset>
                @if(in_array($locale, $form['active_sites']))
                    <span>
                        <strong>Deze versie wordt gebruikt door de {{ $name }} site.</strong><br>Om dit te wijzigen wijs je een andere
                                versie toe aan de {{ $name }} site.
                    </span>
                @else
                    Gebruik deze versie voor de {{ $name }} site
                    <x-chief::button
                        type="button"
                        size="xs"
                        variant="transparent"
                        tabindex="-1"
                        x-on:click="$dispatch('open-dialog', { 'id': 'activate-site-info-{{ $locale }}' })"
                    >
                        <x-chief::icon.information-circle />
                    </x-chief::button>
                @endif

            </x-chief::form.label>

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
        </div>
    @endforeach
</x-chief::form.fieldset>
