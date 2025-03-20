@php
    use Illuminate\Support\Arr;
    use Thinktomorrow\Chief\Sites\ChiefSites;
@endphp

<div class="flex justify-end gap-2">
    <div wire:ignore class="flex items-center gap-2 rounded-lg border border-grey-400">
        <x-chief::tabs :show-nav-as-buttons="true" reference="fragmentLocalesTabs" class="-mb-3">
            @foreach ($currentLocales as $_locale)
                <x-chief::tabs.tab tab-id="{{ $_locale }}"></x-chief::tabs.tab>
            @endforeach
        </x-chief::tabs>

        <x-chief::button variant="grey" wire:click="open">
            <x-chief::icon.more-vertical-circle />
        </x-chief::button>
    </div>

    <x-chief::dialog wired title="Op welke taalversies toont dit fragment" size="xs">
        <div class="block space-y-4 px-4 py-2 text-sm text-grey-700" tabindex="-1">
            @foreach (ChiefLocales::locales() as $locale)
                <x-chief::input.group class="w-64">
                    <div class="flex items-start gap-2">
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

            <x-slot:footer
                x-data="{showSpinner: Livewire.find('{{ $this->getId() }}').entangle('isSaving').live}"
            >
                <button
                    class="btn btn-primary gap-2"
                    x-on:click="
                        $wire.isSaving = true
                        $wire.submit()
                    "
                    type="button"
                >
                    Bewaren
                    <svg
                        x-show="showSpinner"
                        class="h-5 w-5 animate-spin"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                </button>
            </x-slot>
        </div>
    </x-chief::dialog>
</div>
