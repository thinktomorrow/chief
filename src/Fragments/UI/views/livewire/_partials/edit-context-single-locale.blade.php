<x-chief::form.fieldset rule="form.locales">
    @foreach($this->getAvailableLocales() as $locale => $name)
        <div class="flex items-start gap-2">
            <x-chief::form.input.checkbox
                :disabled="in_array($locale, $form['active_sites'])"
                id="context-active-sites-{{ $locale }}"
                wire:key="context-active-sites-{{ $locale }}"
                wire:model="form.active_sites"
                value="{{ $locale }}"
            />
            <x-chief::form.label for="context-active-sites-{{ $locale }}"
                                 class="body-dark body text-sm leading-5 flex justify-start items-center"
                                 unset>
                @if(in_array($locale, $form['active_sites']))
                    <span>
                        <strong>Deze versie wordt momenteel live getoond.</strong><br>Om dit te wijzigen zet je een andere
                    fragmenten tab live voor deze taal
                    </span>
                @else
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
                @endif

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
        </div>
        @endforeach
        </div>


        {{--            <x-chief::form.label for="sites">--}}
        {{--                Toon deze fragmenten enkel op de site--}}
        {{--            </x-chief::form.label>--}}

        {{--            <x-chief::form.description>--}}
        {{--                Dit heeft onmiddellijk effect. Deze fragmenten worden voortaan getoond op de site(s):--}}
        {{--            </x-chief::form.description>--}}

        {{--            <x-chief::multiselect--}}
        {{--                wire:model="form.active_sites"--}}
        {{--                :multiple="true"--}}
        {{--                :options="$this->getAvailableSites()"--}}
        {{--                x-on:click="(e) => {--}}
        {{--                        // Scroll to bottom of modal content so the multiselect dropdown is fully visible--}}
        {{--                        e.target.closest(`[data-slot='content']`).scrollTo({top:9999, behavior: 'smooth'})--}}
        {{--                    }"--}}
        {{--            />--}}
        {{--                <x-chief::form.fieldset rule="form.locales">--}}

        {{--                    <x-chief::form.label for="locales">--}}
        {{--                        Taal selectie--}}
        {{--                    </x-chief::form.label>--}}

        {{--                    <x-chief::form.description>--}}
        {{--                        Fragmenten worden opgemaakt in deze talen.--}}
        {{--                    </x-chief::form.description>--}}

        {{--                    <x-chief::multiselect--}}
        {{--                        wire:model="form.locales"--}}
        {{--                        :multiple="true"--}}
        {{--                        :options="$this->getAvailableLocales()"--}}
        {{--                        x-on:click="(e) => {--}}
        {{--                        // Scroll to bottom of modal content so the multiselect dropdown is fully visible--}}
        {{--                        e.target.closest(`[data-slot='content']`).scrollTo({top:9999, behavior: 'smooth'})--}}
        {{--                    }"--}}
        {{--                    />--}}
        {{--                </x-chief::form.fieldset>--}}
</x-chief::form.fieldset>
