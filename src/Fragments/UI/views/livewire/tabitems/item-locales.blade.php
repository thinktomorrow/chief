<x-chief::form.fieldset rule="form.locales">
    <x-chief::form.label>Voor welke sites wil je deze versie maken?</x-chief::form.label>

    <div data-slot="control" class="divide-y divide-grey-200 rounded-lg border border-grey-200">
        @foreach ($this->getAvailableLocales() as $locale)
            <div class="flex justify-between gap-3 p-3">
                <div class="my-0.5 flex items-start gap-2">
                    <x-chief::form.input.checkbox
                        :disabled="in_array($locale, $form['active_sites'])"
                        id="item-locales-{{ $locale }}"
                        wire:key="item-locales-{{ $locale }}"
                        wire:model.change="form.locales"
                        value="{{ $locale }}"
                    />
                    <x-chief::form.label for="item-active-sites-{{ $locale }}"
                                         class="body-dark body text-sm leading-5 flex justify-start items-center"
                                         unset>
                        {{ \Thinktomorrow\Chief\Sites\ChiefSites::name($locale) }}
                    </x-chief::form.label>
                </div>
            </div>
        @endforeach
    </div>
</x-chief::form.fieldset>
