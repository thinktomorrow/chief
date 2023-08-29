<x-chief::dialog wired size="lg" title="Hotspots">
    @if($isOpen)
        <form class="flex items-start gap-8 max-lg:flex-wrap">
            <div
                class="flex flex-col gap-4 sm:gap-8 lg:gap-4 sm:flex-row lg:flex-col shrink-0 w-full lg:w-[calc(30rem-4rem)]">
                <div
                    x-data="{
                        addHotSpot: (event) => {

                            // Dimensions of container to calculate top / left percentages
                            const containerElement = $refs.hotSpotsContainer;
                            const containerWidth = parseInt(containerElement.getBoundingClientRect().width, 10);
                            const containerHeight = containerElement.getBoundingClientRect().height;

                            // Position of clicked point
                            const rect = event.target.getBoundingClientRect();
                            const x = event.clientX - rect.left;
                            const y = event.clientY - rect.top;

                            $wire.addHotSpot(
                                x,
                                y,
                                (y / containerHeight) * 100, // top
                                (x / containerWidth) * 100, // left
                            );
                        }

                    }"
                    class="flex items-center justify-center w-full sm:w-3/5 lg:w-full">
                    <div x-ref="hotSpotsContainer" class="relative" x-on:click="addHotSpot">

                        @foreach($hotSpots as $hotSpot)
                            <span
                                x-on:click.stop="$wire.activateHotSpot('{{ $hotSpot['id'] }}')"
                                class="absolute p-5 rounded-full bg-green-100"
                                style="top:{{ $hotSpot['top'] }}%; left:{{ $hotSpot['left'] }}%">HOT</span>
                        @endforeach

                        <div
                            class="overflow-hidden rounded-xl bg-grey-100">
                            <img
                                src="{{ $previewFile->previewUrl }}"
                                alt="Preview image"
                                class="object-contain w-full h-full"
                            >
                        </div>


                    </div>
                </div>
            </div>

            <div x-data="{activeIndex: @entangle('activeHotSpotId')}" class="space-y-6 grow">

                @foreach($this->getGroupedComponents() as $hotSpotId => $componentsPerHotSpot)
                    <div x-show="activeIndex == '{{ $hotSpotId }}'" class="pt-6 space-y-2 border-t border-grey-100">
                        <h2 class="text-sm tracking-wider uppercase text-grey-500">Hotspot {{ $hotSpotId }}</h2>
                        <div class="space-y-6">
                            @foreach($componentsPerHotSpot as $component)
                                {{ $component }}
                            @endforeach
                        </div>
                    </div>
                @endforeach

                @if($errors->any())
                    <div class="pt-6 space-y-2 border-t border-grey-100">
                        @foreach($errors->all() as $error)
                            <x-chief::inline-notification type="error">
                                {{ ucfirst($error) }}
                            </x-chief::inline-notification>
                        @endforeach
                    </div>
                @endif

                <x-slot name="footer">
                    <div class="flex flex-wrap justify-end gap-3">

                        <button type="button" x-on:click="open = false" class="btn btn-grey">
                            Annuleren
                        </button>

                        <button wire:click.prevent="submit" type="submit" class="btn btn-primary">
                            Opslaan
                        </button>
                    </div>
                </x-slot>
            </div>
        </form>
    @endif
</x-chief::dialog>
