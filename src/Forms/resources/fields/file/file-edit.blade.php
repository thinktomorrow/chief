<x-chief::dialog wired>
    @if($isOpen)
        <!-- form prevents enter key in fields in this modal context to trigger submits of other form on the page -->
        <form class="flex gap-8">
            <div class="space-y-6 shrink-0">
                @if($previewFile->isImage())
                    <div class="overflow-hidden w-80 h-80 bg-grey-100 rounded-xl">
                        <img
                            src="{{ $previewFile->previewUrl }}"
                            class="object-contain w-full h-full"
                        >
                    </div>
                @endif

                <div class="flex flex-wrap gap-2">
                    @if($previewFile->isImage())
                        <a
                            wire:click="openImageCrop()"
                            title="crop or resize the image"
                            class="cursor-pointer shrink-0 link link-primary"
                        >
                            <x-chief::icon-button color="grey">
                                <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><line x1="64" y1="64" x2="24" y2="64" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><polyline points="64 24 64 192 232 192" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><polyline points="192 160 192 64 96 64" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><line x1="192" y1="232" x2="192" y2="192" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line></svg>
                            </x-chief::icon-button>
                        </a>
                    @endif

                    @if($mediaFile)
                        <x-chief::icon-button color="grey">
                            <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><polyline points="176.2 99.7 224.2 99.7 224.2 51.7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><path d="M65.8,65.8a87.9,87.9,0,0,1,124.4,0l34,33.9" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path><polyline points="79.8 156.3 31.8 156.3 31.8 204.3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><path d="M190.2,190.2a87.9,87.9,0,0,1-124.4,0l-34-33.9" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path></svg>
                        </x-chief::icon-button>

                        <a
                            download
                            href="{{ $mediaFile->url() }}"
                            title="download"
                            class="shrink-0 link link-primary"
                        >
                            <x-chief::icon-button icon="icon-download" color="grey"/>
                        </a>
                    @endif
                </div>

                <div class="space-y-0.5 text-grey-500">
                    <div class="flex justify-between">
                        <span>Bestandsgrootte</span>
                        <span>{{ $previewFile->humanReadableSize }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Bestandsextensie</span>
                        <span>{{ $previewFile->mimeType }}</span>
                    </div>

                    @if($mediaFile)
                        <div class="flex justify-between">
                            <span>Datum toegevoegd</span>
                            <span>05/01/23 12:53</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Datum aangepast</span>
                            <span>11/01/23 07:10</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6 grow">
                <x-chief::input.group rule="basename">
                    <x-chief::input.label for="basename">Bestandsnaam</x-chief::input.label>

                    <x-chief::input.prepend-append :append="'.'.$previewFile->extension">
                        <x-chief::input.text
                            id="basename"
                            name="basename"
                            placeholder="Bestandsnaam"
                            wire:model.lazy="formValues.basename"
                        />
                    </x-chief::input.prepend-append>
                </x-chief::input.group>

                @foreach($this->getComponents() as $component)
                    {{ $component }}
                @endforeach

                <div>
                    <button wire:click.prevent="submit" type="submit" class="btn btn-primary">
                        Bestand opslaan
                    </button>
                </div>
            </div>
        </form>
    @endif
</x-chief::dialog>
