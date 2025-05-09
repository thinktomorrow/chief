<x-chief::form.fieldset rule="form.locales">
    <x-chief::form.label>Voor welke sites wil je deze versie maken?</x-chief::form.label>

    <div data-slot="control" class="space-y-2">
        @foreach ($this->getAvailableLocales() as $locale)
            <div class="flex items-start gap-2">
                <x-chief::form.input.checkbox
                    :disabled="in_array($locale, $form['active_sites'])"
                    id="item-locales-{{ $locale }}"
                    wire:key="item-locales-{{ $locale }}"
                    wire:model.change="form.locales"
                    value="{{ $locale }}"
                />

                <x-chief::form.label for="item-locales-{{ $locale }}" class="body-dark body leading-5" unset>
                    {{ \Thinktomorrow\Chief\Sites\ChiefSites::name($locale) }}
                </x-chief::form.label>
            </div>
        @endforeach
    </div>
</x-chief::form.fieldset>
