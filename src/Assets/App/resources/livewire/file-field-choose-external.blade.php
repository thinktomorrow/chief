<x-chief::dialog wired>
    @if($isOpen)

        <div class="w-full lg:w-[46rem] max-h-[80vh] flex flex-col gap-6 overflow-y-auto">
            <div class="gap-3 shrink-0">
                <div class="relative gap-3 space-y-4">

                    @if($assetId)
                        <h3 class="h3">Vervang de externe link</h3>
                    @else
                        <h3 class="h3">Voeg een {{ \Illuminate\Support\Arr::join($driverTypes,',',' of ') }} toe</h3>
                    @endif

                    <x-chief::input.group>
                        <x-chief::input.label for="driverType" unset class="font-medium h6 body-dark">
                            Platform
                        </x-chief::input.label>

                        <div class="flex flex-wrap items-start gap-1">
                            <x-chief::input.select
                                id="driverType"
                                wire:model="driverType"
                                class=""
                            >
                                @foreach($driverTypes as $driverTypeOption)
                                    <option value="{{ $driverTypeOption }}">{{ ucfirst($driverTypeOption) }}</option>
                                @endforeach
                            </x-chief::input.select>
                        </div>
                    </x-chief::input.group>

                    @if($driverType)
                        <x-chief::input.group>
                            <x-chief::input.label for="driverId" unset class="font-medium h6 body-dark">
                                De {{ $driverType }} id of link.
                            </x-chief::input.label>

                            <div class="flex flex-wrap items-start gap-1">
                                <x-chief::input.text
                                    id="driverId"
                                    wire:model="driverId"
                                    placeholder="de ID of link van het externe bestand"
                                    class="w-full"
                                />
                            </div>
                        </x-chief::input.group>
                    @endif

                    <x-chief::input.description>
                        {!! $this->getContent() !!}
                    </x-chief::input.description>

                </div>

                <div class="space-y-2">
                    @foreach($errors->all() as $error)
                        <x-chief::inline-notification type="error">
                            {{ ucfirst($error) }}
                        </x-chief::inline-notification>
                    @endforeach
                </div>

            </div>

            <div class="flex flex-wrap justify-end gap-3 max-lg:w-full shrink-0">
                <button wire:click="save" type="button" class="btn btn-primary shrink-0">
                    @if($assetId)
                        Vervang extern bestand
                    @else
                        Voeg extern bestand toe
                    @endif
                </button>
            </div>
        </div>
    @endif
</x-chief::dialog>
