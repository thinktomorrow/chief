<x-chief::dialog.modal wired size="lg" title="Beheer de hotspots op deze afbeelding">
    @if ($isOpen)
        <form
            class="gutter-4 flex flex-wrap items-start justify-start"
            x-data="{
                activeIndex: @entangle('activeHotSpotId'),
                addHotSpot: (event) => {
                    // Dimensions of container to calculate top / left percentages
                    const containerElement = $refs.hotSpotsContainer
                    const containerWidth = parseInt(
                        containerElement.getBoundingClientRect().width,
                        10,
                    )
                    const containerHeight = containerElement.getBoundingClientRect().height

                    // Position of clicked point
                    const rect = event.target.getBoundingClientRect()
                    const x = event.clientX - rect.left
                    const y = event.clientY - rect.top

                    $wire.addHotSpot(
                        x,
                        y,
                        (y / containerHeight) * 100,
                        (x / containerWidth) * 100,
                    )
                },
            }"
        >
            <div class="flex w-full items-center justify-center sm:w-3/5">
                <div x-ref="hotSpotsContainer" x-on:click="addHotSpot" class="relative" style="cursor: crosshair">
                    @foreach ($hotSpots as $hotSpot)
                        <div
                            class="absolute -translate-x-2 -translate-y-2"
                            style="top: {{ $hotSpot['top'] }}%; left: {{ $hotSpot['left'] }}%"
                        >
                            <div
                                class="absolute h-4 w-4 origin-center rounded-full bg-white"
                                x-bind:class="{ 'animate-ping': '{{ $hotSpot['id'] }}' === activeIndex }"
                            ></div>

                            <div
                                x-on:click.stop="$wire.activateHotSpot('{{ $hotSpot['id'] }}')"
                                class="hover:bg-primary relative h-4 w-4 cursor-pointer rounded-full bg-white shadow-lg ring-2 ring-black/10 transition-all duration-100 ease-in-out"
                                x-bind:class="{
                                    '!bg-primary-500 !border !border-primary-600':
                                        '{{ $hotSpot['id'] }}' === activeIndex,
                                }"
                            ></div>
                        </div>
                    @endforeach

                    <div class="bg-grey-100 overflow-hidden rounded-xl">
                        <img
                            src="{{ $previewFile->getUrl('large') }}"
                            alt="Preview image"
                            class="h-full w-full object-contain"
                        />
                    </div>
                </div>
            </div>

            <div class="w-full sm:w-2/5">
                @forelse ($this->getGroupedComponents() as $hotSpotId => $componentsPerHotSpot)
                    <div wire:key="{{ $hotSpotId }}" x-show="activeIndex == '{{ $hotSpotId }}'" class="space-y-6">
                        @foreach ($componentsPerHotSpot as $component)
                            {{ $component }}
                        @endforeach

                        <x-chief::button type="button" wire:click="removeHotSpot('{{ $hotSpotId }}')" variant="grey">
                            Verwijder deze hotspot
                        </x-chief::button>
                    </div>
                @empty
                    <div class="space-y-2">
                        <h2 class="body-dark body font-medium">Je hebt nog geen hotspots toegevoegd.</h2>

                        <p class="body text-grey-500">
                            Om een hotspot toe te voegen klik je op de afbeelding op de plaats waar je een hotspot wil
                            toevoegen. Daarna kan je hier de inhoud van de hotspot aanpassen.
                        </p>
                    </div>
                @endforelse

                @if ($errors->any())
                    <x-chief::callout variant="red" class="mt-6">
                        @foreach ($errors->all() as $error)
                            <p>{{ ucfirst($error) }}</p>
                        @endforeach
                    </x-chief::callout>
                @endif

                <x-slot name="footer">
                    <x-chief::dialog.modal.footer>
                        <x-chief::button x-on:click="$wire.close()">Annuleren</x-chief::button>

                        <x-chief::button wire:click.prevent="submit" variant="blue" type="submit">
                            Opslaan
                        </x-chief::button>
                    </x-chief::dialog.modal.footer>
                </x-slot>
            </div>
        </form>
    @endif
</x-chief::dialog.modal>
