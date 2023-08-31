<x-chief::dialog wired size="md" title="Voeg bestanden toe">
    @if($isOpen)
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
                    <livewire:chief-wire::file-edit parent-id="{{ $this->getId() }}" :components="$this->components"/>
                </div>
            </x-chief-assets::upload-and-dropzone>
        </form>

        <x-slot name="footer" class="flex justify-end">
            <button type="submit" form="file-upload-form-{{ $this->getId() }}" @disabled($this->countFiles() < 1) @class([
                'btn btn-primary',
                'btn-disabled' => $this->countFiles() < 1,
            ])>
                Voeg {{ $this->countFiles() > 1 ? $this->countFiles() . ' bestanden' : 'bestand' }} toe
            </button>
        </x-slot>
    @endif
</x-chief::dialog>
