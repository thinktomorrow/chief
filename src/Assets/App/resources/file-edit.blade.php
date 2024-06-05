<x-chief::dialog wired size="md" title="{{ $previewFile && $previewFile->isExternalAsset ? 'Pas extern bestand aan' : 'Pas bestand aan' }}">
    @if($previewFile && $previewFile->isExternalAsset)
        @include('chief-assets::livewire.file-edit-external')
    @else
        @include('chief-assets::livewire.file-edit-local')
    @endif
</x-chief::dialog>

