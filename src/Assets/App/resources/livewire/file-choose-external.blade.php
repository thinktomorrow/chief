<x-chief::dialog
        wired
        size="xs"
        :title="$assetId ? 'Vervang extern bestand' : 'Voeg een link naar een extern bestand toe'"
>
    @if($isOpen)
        <div class="space-y-4">
            <x-chief::input.group>
                <x-chief::input.label for="driverType">
                    Platform
                </x-chief::input.label>

                <x-chief::input.select id="driverType" wire:model.live="driverType" container-class="w-full">
                    @foreach($driverTypes as $driverTypeOption)
                        <option value="{{ $driverTypeOption }}">{{ ucfirst($driverTypeOption) }}</option>
                    @endforeach
                </x-chief::input.select>
            </x-chief::input.group>

            @if($driverType)
                <x-chief::input.group>
                    <x-chief::input.label for="driverId">
                        {{ $this->getLabel() }}
                    </x-chief::input.label>

                    <x-chief::input.description>
                        {!! $this->getDescription() !!}
                    </x-chief::input.description>

                    <x-chief::input.text
                        x-data="{}" {{-- Prevents directive to be triggered twice --}}
                        x-prevent-submit-on-enter
                        id="driverId"
                        wire:model="driverId"
                        placeholder="{{ $this->getLabel() }}"
                        class="w-full"
                    />
                </x-chief::input.group>
            @endif

            @if($errors && count($errors) > 0)
                <x-chief::inline-notification type="error" size="small" class="w-full">
                    @foreach ($errors->all() as $error)
                        <p>{{ ucfirst($error) }}</p>
                    @endforeach
                </x-chief::inline-notification>
            @endif
        </div>

        <x-slot name="footer">
            <button wire:click="save" type="button" class="btn btn-primary">
                @if($assetId)
                    Vervang extern bestand
                @else
                    Voeg extern bestand toe
                @endif
            </button>
        </x-slot>
    @endif
</x-chief::dialog>
