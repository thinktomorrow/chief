<div>
    @if($previewFile->isImage())
        <a wire:click="openImageHotSpots()" title="Image hotspot tool" class="shrink-0">
            <x-chief::icon-button icon="icon-crop"/>
        </a>

        @if(is_array($previewFile->getData('hotspots')) && $hotSpotCount = count($previewFile->getData('hotspots')))
            <span class="text-sm">
                {{ $hotSpotCount > 1 ? $hotSpotCount  . ' hotspots' : '1 hotspot' }}
            </span>
        @endif

    @endif
</div>
