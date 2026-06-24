<div>
    @if ($previewFile->isImage() && $previewFile->mediaId)
        <x-chief::button
            wire:click="openImageHotSpots()"
            title="Image hotspot tool"
            class="shrink-0"
            variant="grey"
            size="sm"
        >
            <x-chief::icon.pin-location />
            <span>Voeg hotspots toe</span>
            @if (is_array($previewFile->getData('hotspots')) && ($hotSpotCount = count($previewFile->getData('hotspots'))))
                <span
                    data-slot="icon"
                    class="ml-0.25 flex items-center justify-center rounded-full bg-black/10 text-xs font-medium"
                >
                    {{ $hotSpotCount }}
                </span>
            @endif
        </x-chief::button>
    @endif
</div>
