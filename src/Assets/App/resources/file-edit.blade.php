@if($previewFile && $previewFile->isExternalAsset)
    @if(count($this->getComponents()) > 0)
        <x-chief::dialog wired size="md" title="Pas extern bestand aan">
            @include('chief-assets::livewire.file-edit-external')
        </x-chief::dialog>
    @else
        <x-chief::dialog wired size="md" title="Pas extern bestand aan">
            @include('chief-assets::livewire.file-edit-external')
        </x-chief::dialog>
    @endif
@else
    <x-chief::dialog wired size="md" title="Pas bestand aan">
        @include('chief-assets::livewire.file-edit-local')
    </x-chief::dialog>
@endif
