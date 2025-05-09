<div>
    @if ($previewFile->isImage() && $previewFile->mediaId)
        <x-chief::button wire:click="openImageHotSpots()" title="Image hotspot tool" class="shrink-0">
            <span class="inline-flex gap-1.5">
                Voeg hotspots toe

                @if (is_array($previewFile->getData('hotspots')) && ($hotSpotCount = count($previewFile->getData('hotspots'))))
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-black/10 text-xs font-medium">
                        {{ $hotSpotCount }}
                    </span>
                @endif
            </span>
        </x-chief::button>
    @endif
</div>
