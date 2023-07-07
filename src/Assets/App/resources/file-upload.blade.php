<x-chief::dialog wired>
    @if($isOpen)
        <!-- form prevents enter key in fields in this modal context to trigger submits of other form on the page -->
        <form wire:submit.prevent="submit(Object.fromEntries(new FormData($event.target)))" class="p-4 space-y-4 gap-8 w-full xs:w-96 sm:w-128 md:w-160 lg:w-192 max-h-[80vh] overflow-y-auto">

                <x-chief-assets::upload-and-dropzone>
                    {{ $this->filePreview }}

                    @error('files.0')
                    <x-chief::inline-notification type="error" class="mt-2">
                        {{ ucfirst($message) }}
                    </x-chief::inline-notification>
                    @enderror

                    {{ $this->fileSelect }}

                    <div>
                        <livewire:chief-wire::file-edit
                            parent-id="{{ $this->id }}"
                            :components="$this->components"
                        />
                    </div>

                </x-chief-assets::upload-and-dropzone>

            <div>
                <button type="submit" {{ $this->countFiles() < 1 ? 'disabled' : '' }}
                    @class([
                        'btn btn-grey' => $this->countFiles() < 1,
                        'btn btn-primary' => $this->countFiles() > 0,
                    ])>
                    Voeg {{ $this->countFiles() > 1 ? $this->countFiles() . ' bestanden' : 'bestand' }} toe</button>
            </div>


        </form>
    @endif
</x-chief::dialog>
