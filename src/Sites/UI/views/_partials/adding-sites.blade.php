@foreach ($this->getNonAddedSites() as $site)
    <x-chief::input.group class="w-full">
        <div class="flex items-start gap-2">
            <x-chief::input.checkbox
                id="{{ $site->id }}"
                wire:model="addingSiteIds"
                value="{{ $site->id }}"
            ></x-chief::input.checkbox>
            <x-chief::form.label for="{{ $site->id }}">{{ $site->name }}</x-chief::form.label>
        </div>
    </x-chief::input.group>
@endforeach

<x-slot name="footer">
    <x-chief::dialog.modal.footer>
        <x-chief-table::button wire:click="closeAddingSites">Annuleer</x-chief-table::button>
        <x-chief-table::button wire:click="saveAddingSites" variant="blue">Toevoegen</x-chief-table::button>
    </x-chief::dialog.modal.footer>
</x-slot>
