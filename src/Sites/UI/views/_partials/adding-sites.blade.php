@foreach($this->getNonAddedSites() as $site)
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
    <x-chief-table::button wire:click="closeAddingSites" class="shrink-0">Annuleer
    </x-chief-table::button>
    <x-chief-table::button wire:click="saveAddingSites" variant="blue" class="shrink-0">
        Toevoegen
    </x-chief-table::button>
</x-slot>
