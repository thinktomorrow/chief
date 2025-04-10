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
                </div>

            </div>

        @endforeach
    </div>
</x-chief::form.fieldset>
