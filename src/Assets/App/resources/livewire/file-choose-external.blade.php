<x-chief::dialog.modal
    wired
    size="xs"
    :title="$assetId ? 'Vervang extern bestand' : 'Voeg een link naar een extern bestand toe'"
>
    @if ($isOpen)
        <x-chief::form.fieldset>
            <x-chief::form.label for="driverType">Platform</x-chief::form.label>

            <x-chief::form.input.select id="driverType" wire:model.live="driverType">
                @foreach ($driverTypes as $driverTypeOption)
                    <option value="{{ $driverTypeOption }}">{{ ucfirst($driverTypeOption) }}</option>
                @endforeach
            </x-chief::form.input.select>
        </x-chief::form.fieldset>

        @if ($driverType)
            <x-chief::form.fieldset>
                <x-chief::form.label for="driverId">
                    {{ $this->getLabel() }}
                </x-chief::form.label>

                <x-chief::form.description>
                    {!! $this->getDescription() !!}
                </x-chief::form.description>

                <x-chief::form.input.text
                    x-data="{}"
                    {{-- Prevents directive to be triggered twice --}}
                    x-prevent-submit-on-enter
                    id="driverId"
                    wire:model="driverId"
                    placeholder="{{ $this->getLabel() }}"
                    class="w-full"
                />
            </x-chief::form.fieldset>
        @endif

        @if ($errors->any())
            <x-chief::callout data-slot="form-group" size="small" variant="red" class="w-full">
                @foreach ($errors->all() as $error)
                    <p>{{ ucfirst($error) }}</p>
                @endforeach
            </x-chief::callout>
        @endif

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief::button wire:click="save" variant="blue" type="button">
                    @if ($assetId)
                        Vervang extern bestand
                    @else
                        Voeg extern bestand toe
                    @endif
                </x-chief::button>
            </x-chief::dialog.modal.footer>
        </x-slot>
    @endif
</x-chief::dialog.modal>
