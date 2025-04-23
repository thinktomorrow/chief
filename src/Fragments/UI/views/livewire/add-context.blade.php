<x-chief::dialog.modal
    wired
    size="sm"
    title="Voeg versie toe"
    subtitle="Je kan meerdere versies beheren. Zo kan je specifieke fragmenten per site voorzien."
    x-data="{ duplicateFromSelection: '0' }"
>
    @if ($isOpen)
        <x-chief::form.fieldset rule="form.title">
            <x-chief::form.label for="title">Titel van de nieuwe versie</x-chief::form.label>
            <x-chief::form.input.text id="title" wire:model="form.title" />
        </x-chief::form.fieldset>

        <x-chief::form.fieldset>
            <div data-slot="control" class="space-y-2">
                <div class="flex items-start gap-2">
                    <x-chief::form.input.radio
                        id="duplicate-from-no"
                        wire:model.change="form.duplicate_from"
                        x-model="duplicateFromSelection"
                        value="0"
                    />

                    <x-chief::form.label for="duplicate-from-no" class="body-dark body leading-5" unset>
                        Maak een volledig nieuwe versie
                    </x-chief::form.label>
                </div>

                <div class="flex items-start gap-2">
                    <x-chief::form.input.radio
                        id="duplicate-from-yes"
                        wire:model.change="form.duplicate_from"
                        x-model="duplicateFromSelection"
                        value="1"
                    />

                    <x-chief::form.label for="duplicate-from-yes" unset class="body-dark body leading-5">
                        Start van een bestaande versie
                    </x-chief::form.label>
                </div>
            </div>
        </x-chief::form.fieldset>

        <x-chief::form.fieldset x-show="duplicateFromSelection === '1'">
            <x-chief::form.label>Kies een versie</x-chief::form.label>
            <x-chief::form.input.select wire:model.change="form.duplicate_from_item_id">
                @foreach ($this->getItems() as $item)
                    <option wire:key="duplicate-from-item-{{ $item->id }}" value="{{ $item->getId() }}">
                        {{ $item->getTitle() }}
                    </option>
                @endforeach
            </x-chief::form.input.select>
        </x-chief::form.fieldset>

        @if (count($this->getAvailableLocales()) > 1)
            @include('chief-fragments::livewire.tabitems.item-locales')
        @endif

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
                <x-chief::button wire:click="save" variant="blue">Toevoegen</x-chief::button>
            </x-chief::dialog.modal.footer>
        </x-slot>
    @endif
</x-chief::dialog.modal>
