<x-chief::form.fieldset class="w-full space-y-2">
    @foreach ($this->getNonAddedSites() as $site)
        {{--
            <div class="flex items-start gap-2">
            <x-chief::form.input.checkbox
            id="{{ $site->locale }}"
            wire:model="addingLocales"
            value="{{ $site->locale }}"
            />
            <x-chief::form.label for="{{ $site->locale }}">{{ $site->name }}</x-chief::form.label>
            </div>
        --}}

        <label class="flex items-start gap-2 rounded-xl border border-grey-200 p-2">
            <x-chief::form.input.checkbox
                wire:model="addingLocales"
                id="{{ $site->locale }}"
                value="{{ $site->locale }}"
                class="peer"
            />

            <label for="{{ $site->locale }}" class="leading-5">
                {{ $site->name }}
                {{ $site->url }}
                {{ $site->shortName }}
                {{ $site->locale }}
                {{ $site->fallbackLocale }}
            </label>
        </label>
    @endforeach
</x-chief::form.fieldset>

<x-slot name="footer">
    <x-chief::dialog.drawer.footer>
        <x-chief::button wire:click="saveAddingSites" variant="blue">Toevoegen</x-chief::button>
        <x-chief::button wire:click="closeAddingSites">Annuleer</x-chief::button>
    </x-chief::dialog.drawer.footer>
</x-slot>
