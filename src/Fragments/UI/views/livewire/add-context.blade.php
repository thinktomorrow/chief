<x-chief::dialog.modal wired size="sm" title="Voeg tab toe"
                       subtitle="Je kan meerdere tabs met fragmenten beheren. Zo kan je specifieke fragmenten per site voorzien.">
    @if ($isOpen)

        @if(count($this->getAvailableLocales()) > 1)
            @include('chief-fragments::livewire.tabitems.item-locales')
        @endif

        <x-chief::form.fieldset class="pt-3">
            <x-chief::form.label>Hoe wil je starten?</x-chief::form.label>

            <div>
                <div class="space-y-3">
                    <div class="flex items-start gap-2">
                        <x-chief::form.input.radio
                            id="duplicate-from-no"
                            wire:model.change="form.duplicate_from"
                            value="0"
                        />

                        <x-chief::form.label for="duplicate-from-no" class="body-dark body leading-5" unset>
                            Start met een lege tab
                        </x-chief::form.label>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-chief::form.input.radio
                            id="duplicate-from-yes"
                            wire:model.change="form.duplicate_from"
                            value="1"
                        />

                        <x-chief::form.label for="duplicate-from-yes" class="body-dark body leading-5" unset>
                            Dupliceer de tabinhoud van:

                            <x-chief::form.input.select wire:model.change="form.duplicate_from_item_id">
                                @foreach ($this->getItems() as $item)
                                    <option wire:key="duplicate-from-item-{{ $item->id }}" value="{{ $item->getId() }}">
                                        {{ $item->getTitle() }}
                                    </option>
                                @endforeach
                            </x-chief::form.input.select>
                        </x-chief::form.label>
                    </div>
                </div>
            </div>
        </x-chief::form.fieldset>

        {{--        <x-chief::form.fieldset rule="form.title">--}}
        {{--            <x-chief::form.label for="title">Titel</x-chief::form.label>--}}
        {{--            <x-chief::form.input.text id="title" wire:model="form.title" />--}}
        {{--        </x-chief::form.fieldset>--}}

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
                <x-chief::button wire:click="save" variant="blue">Toevoegen</x-chief::button>
            </x-chief::dialog.modal.footer>
        </x-slot>
    @endif
</x-chief::dialog.modal>
