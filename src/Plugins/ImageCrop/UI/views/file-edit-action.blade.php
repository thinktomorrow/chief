<div>
    {{-- Cropping --}}
    @if ($previewFile->isImage())
        <x-chief::button
            size="sm"
            variant="outline-white"
            wire:click="openImageCrop()"
            title="Image crop/resize tool"
            class="shrink-0"
        >
            <x-chief::icon.crop />
        </x-chief::button>
    @endif
</div>
