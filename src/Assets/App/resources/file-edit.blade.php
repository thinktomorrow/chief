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

                    @if($previewFile)
                        <div x-cloak x-data="{showReplaceActions: false}">
                            <x-chief::icon-button color="grey" x-on:click="showReplaceActions = true">
                                <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><polyline points="176.2 99.7 224.2 99.7 224.2 51.7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><path d="M65.8,65.8a87.9,87.9,0,0,1,124.4,0l34,33.9" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path><polyline points="79.8 156.3 31.8 156.3 31.8 204.3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><path d="M190.2,190.2a87.9,87.9,0,0,1-124.4,0l-34-33.9" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path></svg>
                            </x-chief::icon-button>

                            <div x-show="showReplaceActions">

                                <div class="flex border border-dashed divide-x rounded-lg shadow-sm border-grey-200 divide-grey-200 divide-dashed">

                                    <label for="{{ $this->id }}" class="relative w-1/2">

                                        <div x-data="{isUploading: false, isDone: false, progress: 0}"
                                             x-show="isUploading"
                                             x-on:livewire-upload-start="isUploading = true"
                                             x-on:livewire-upload-finish="() => {}"
                                             x-on:livewire-upload-error="isUploading = false"
                                             x-on:livewire-upload-progress="progress = $event.detail.progress"
                                        >

                                            <input
                                                wire:model="file"
                                                type="file"
                                                id="{{ $this->id }}"
                                                class="absolute inset-0 w-full opacity-0 cursor-pointer pointer-events-auto peer"
                                            />

                                            <progress class="w-full" max="100" x-bind:value="progress"></progress>
                                        </div>

                                        <div class="flex items-center gap-4 p-4 rounded-l-lg group peer-focus:ring-1 peer-focus:ring-primary-500">
                                            <div class="flex items-center justify-center w-12 h-12 rounded-full shrink-0 group-hover:bg-primary-50 bg-grey-100">
                                                <svg class="w-6 h-6 text-black transition-all duration-75 ease-in-out group-hover:text-primary-500 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /> </svg>
                                            </div>

                                            <div class="space-y-0.5 leading-tight">
                                                <p class="text-black">
                                                    Upload een nieuw bestand
                                                </p>
                                            </div>
                                        </div>
                                    </label>

                                    <a wire:click="openFilesChoose" class="cursor-pointer flex items-center w-1/2 gap-4 p-4 rounded-r-lg group">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-full shrink-0 group-hover:bg-primary-50 bg-grey-100">
                                            <svg class="w-5 h-5 text-black transition-all duration-75 ease-in-out group-hover:text-primary-500 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /> </svg>
                                        </div>

                                        <div class="space-y-0.5 leading-tight">
                                            <span class="text-black">
                                                Kies uit de mediabibliotheek
                                            </span>
                                        </div>
                                    </a>
                                </div>

                            </div>
                        </div>


                        <a
                            download
                            href="{{ $previewFile->getUrl() }}"
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

                    @if($previewFile)
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
                            wire:model.lazy="form.basename"
                        />
                    </x-chief::input.prepend-append>
                </x-chief::input.group>

                @if(count($this->getComponents()) > 0)
                    <div class="bg-blue-50 p-4 rounded space-y-4">

                        <h2 class="text-blue-500">Gegevens op deze pagina</h2>

                        @foreach($this->getComponents() as $component)
                            {{ $component }}
                        @endforeach
                    </div>
                @endif

                <div>
                    @foreach($errors->all() as $error)
                        <span class="text-red-500">{{ $error }}</span>
                    @endforeach
                </div>

                <div>
                    <button wire:click.prevent="submit" type="submit" class="btn btn-primary">
                        Opslaan
                    </button>
                </div>
            </div>
        </form>
    @endif

    <div><livewire:chief-wire::files-choose parent-id="{{ $this->id }}" /></div>

</x-chief::dialog>
