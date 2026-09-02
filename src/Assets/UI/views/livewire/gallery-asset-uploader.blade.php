<x-chief::dialog.modal wired size="sm" title="Voeg bestanden toe">
    @if ($isOpen)
        <!-- form prevents enter key in fields in this modal context to trigger submits of other form on the page -->
        <form
            id="file-upload-form-{{ $this->getId() }}"
            wire:submit.prevent="submit(Object.fromEntries(new FormData($event.target)))"
        >
            <x-chief-assets::upload-and-dropzone>
                {{ $this->filePreview }}

                @error ('files.0')
                    <x-chief::callout size="small" variant="red" class="mt-2">
                        <p>{{ ucfirst($message) }}</p>
                    </x-chief::callout>
                @enderror

                {{ $this->fileSelect }}

                <template x-teleport="body">
                    <livewire:chief-wire-assets::external-file-field-asset-chooser
                        parent-id="{{ $this->getId() }}"
                        allowMultiple="{{ $allowMultiple }}"
                    />
                </template>

                <template x-teleport="body">
                    <livewire:chief-wire-assets::gallery-asset-editor
                        parent-id="{{ $this->getId() }}"
                        :components="$this->components"
                    />
                </template>
            </x-chief-assets::upload-and-dropzone>
        </form>

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                @if ($this->countUploadedOrSelectedFiles() < 1)
                    <x-chief::button variant="blue" type="submit" form="file-upload-form-{{ $this->getId() }}" disabled>
                        Voeg {{ $this->countUploadedOrSelectedFiles() > 1 ? $this->countUploadedOrSelectedFiles() . ' bestanden' : 'bestand' }} toe
                    </x-chief::button>
                @else
                    <x-chief::button variant="blue" type="submit" form="file-upload-form-{{ $this->getId() }}">
                        Voeg {{ $this->countUploadedOrSelectedFiles() > 1 ? $this->countUploadedOrSelectedFiles() . ' bestanden' : 'bestand' }} toe
                    </x-chief::button>
                @endif
            </x-chief::dialog.modal.footer>
        </x-slot>
    @endif
</x-chief::dialog.modal>
