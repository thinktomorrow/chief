<x-chief::dialog.modal wired size="md" title="Voeg bestanden toe">
    @if ($isOpen)
        <!-- form prevents enter key in fields in this modal context to trigger submits of other form on the page -->
        <form
            id="file-upload-form-{{ $this->getId() }}"
            wire:submit.prevent="submit(Object.fromEntries(new FormData($event.target)))"
        >
            <x-chief-assets::upload-and-dropzone>
                {{ $this->filePreview }}

                @error('files.0')
                    <x-chief::inline-notification type="error" class="mt-2">
                        {{ ucfirst($message) }}
                    </x-chief::inline-notification>
                @enderror

                {{ $this->fileSelect }}

                <div>
                    <livewire:chief-wire::file-field-choose-external
                        parent-id="{{ $this->getId() }}"
                        allowMultiple="{{ $allowMultiple }}"
                    />
                </div>

                <div>
                    <livewire:chief-wire::file-edit parent-id="{{ $this->getId() }}" :components="$this->components" />
                </div>
            </x-chief-assets::upload-and-dropzone>
        </form>

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                @if ($this->countUploadedOrSelectedFiles() < 1)
                    <x-chief::button variant="grey" type="submit" form="file-upload-form-{{ $this->getId() }}" disabled>
                        Voeg
                        {{ $this->countUploadedOrSelectedFiles() > 1 ? $this->countUploadedOrSelectedFiles() . ' bestanden' : 'bestand' }}
                        toe
                    </x-chief::button>
                @else
                    <x-chief::button variant="blue" type="submit" form="file-upload-form-{{ $this->getId() }}">
                        Voeg
                        {{ $this->countUploadedOrSelectedFiles() > 1 ? $this->countUploadedOrSelectedFiles() . ' bestanden' : 'bestand' }}
                        toe
                    </x-chief::button>
                @endif
            </x-chief::dialog.modal.footer>
        </x-slot>
    @endif
</x-chief::dialog.modal>
