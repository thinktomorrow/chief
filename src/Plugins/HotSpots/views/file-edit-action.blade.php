<div>
    @if($previewFile->isImage())
        <a wire:click="openImageHotSpots()" title="Image hotspot tool" class="shrink-0">
            <x-chief::icon-button icon="icon-crop"/>
        </a>
    @endif
</div>
