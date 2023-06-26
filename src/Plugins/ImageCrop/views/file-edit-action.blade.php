<div>
    {{-- Cropping --}}
    @if($previewFile->isImage())
        <a wire:click="openImageCrop()" title="Image crop/resize tool" class="shrink-0">
            <x-chief::icon-button icon="icon-crop"/>
        </a>
    @endif
</div>
