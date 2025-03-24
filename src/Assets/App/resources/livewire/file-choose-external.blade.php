<x-chief::dialog.modal
    wired
    size="xs"
    :title="$assetId ? 'Vervang extern bestand' : 'Voeg een link naar een extern bestand toe'"
>
    @if ($isOpen)
        <div class="space-y-4">
            <x-chief::input.group>
                <x-chief::form.label for="driverType">Platform</x-chief::form.label>

                <x-chief::input.select id="driverType" wire:model.live="driverType">
                    @foreach ($driverTypes as $driverTypeOption)
                        <option value="{{ $driverTypeOption }}">{{ ucfirst($driverTypeOption) }}</option>
                    @endforeach
                </x-chief::input.select>
            </x-chief::input.group>

            @if ($driverType)
                <x-chief::input.group>
                    <x-chief::form.label for="driverId">
                        {{ $this->getLabel() }}
                    </x-chief::form.label>

                    <x-chief::form.description>
                        {!! $this->getDescription() !!}
                    </x-chief::form.description>

                    <x-chief::input.text
                        x-data="{}"
                        {{-- Prevents directive to be triggered twice --}}
                        x-prevent-submit-on-enter
                        id="driverId"
                        wire:model="driverId"
                        placeholder="{{ $this->getLabel() }}"
                        class="w-full"
                    />
                </x-chief::input.group>
            @endif

            @if ($errors && count($errors) > 0)
                <x-chief::inline-notification type="error" size="small" class="w-full">
                    @foreach ($errors->all() as $error)
                        <p>{{ ucfirst($error) }}</p>
                    @endforeach
                </x-chief::inline-notification>
            @endif
        </div>

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
