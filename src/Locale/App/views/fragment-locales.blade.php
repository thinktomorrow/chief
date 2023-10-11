@php use Illuminate\Support\Arr;use Thinktomorrow\Chief\Locale\ChiefLocaleConfig; @endphp
<div class="flex justify-end gap-2">

    <div wire:ignore class="border border-grey-400 flex gap-2 items-center rounded-lg">
        <x-chief::tabs :show-nav-as-buttons="true" reference="modelLocalesTabs"
                       class="-mb-3">
            @foreach($currentLocales as $_locale)
                <x-chief::tabs.tab tab-id='{{ $_locale }}'></x-chief::tabs.tab>
            @endforeach
        </x-chief::tabs>

        <x-chief::button wire:click="open" class="cursor-pointer">
            <svg class="w-5 h-5">
                <use xlink:href="#icon-ellipsis-vertical"/>
            </svg>
        </x-chief::button>
    </div>


    <x-chief::dialog wired title="Taalversies van deze pagina" size="xs">
        <div class="block px-4 py-2 text-sm text-grey-700 space-y-4" tabindex="-1">
            @foreach(ChiefLocaleConfig::getLocales() as $locale)
                <x-chief::input.group class="w-64">
                    <div class="flex items-start gap-2 ">
                        <x-chief::input.checkbox
                            id="{{ $locale }}"
                            :checked="in_array($locale, $activeLocales)"
                            wire:model.live="activeLocales"
                            value="{{ $locale }}"
                        ></x-chief::input.checkbox>
                        <x-chief::input.label for="{{ $locale }}">{{ $locale }}</x-chief::input.label>
                    </div>
                </x-chief::input.group>
            @endforeach

            @if($this->isAboutToRemoveLocales())
                <div class="bg-red-50 border border-red-100 p-6 rounded-lg space-y-6">
                    Opgelet! Als u de talen
                    <strong>{{ $this->getRemovedLocalesAsString() }}</strong>
                    verwijderd,
                    zullen ook de links en inhoud worden verwijderd van deze pagina.
                </div>
            @endif

            <x-slot:footer x-data="{showSpinner: Livewire.find('{{ $this->getId() }}').entangle('isSaving').live}">
                @if($showConfirmButton)
                    <button class="gap-2 btn btn-error" x-on:click="showSpinner = true; $wire.submit()"
                            type="button">Ja, toch
                        <strong>{{ $this->getRemovedLocalesAsString() }}</strong> verwijderen
                        <svg x-show="showSpinner" class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button class="gap-2 btn btn-link" wire:click="close" type="button">Annuleren</button>
                @else
                    <button class="gap-2 btn btn-primary"
                            x-on:click="$wire.isSaving = true; $wire.submit()"
                            type="button">Bewaren
                        <svg x-show="showSpinner" class="w-5 h-5 animate-spin"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                @endif
            </x-slot:footer>
        </div>
    </x-chief::dialog>

</div>


