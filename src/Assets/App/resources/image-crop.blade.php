<div x-cloak x-data="{open:@entangle('isOpen')}" x-show="open"
     class="fixed inset-0 flex items-center justify-center z-[100]">
    @if($isOpen)
        <div class="absolute inset-0 bg-black/50"></div>

        <div class="relative p-12 bg-white rounded-xl">

            <button class="btn btn-primary-outline" type="button" x-on:click="close()">X</button>

            <h1>CROPPING</h1>

            <!-- form prevents enter key in fields in this modal context to trigger submits of other form on the page -->
            <form>
                <div class="flex items-start gap-12">
                    <div class="space-y-6 shrink-0">
                        <div class="overflow-hidden w-80 h-80 bg-grey-100 rounded-xl">
                            <img
                                    id="image_crop_{{ $previewFile->id }}"
                                    src="{{ $previewFile->previewUrl }}"
                                    class="object-contain w-full h-full"
                            >
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <x-chief::icon-button color="grey">
                                <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                     viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none"></rect>
                                    <line x1="64" y1="64" x2="24" y2="64" fill="none" stroke="currentColor"
                                          stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                                    <polyline points="64 24 64 192 232 192" fill="none" stroke="currentColor"
                                              stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="16"></polyline>
                                    <polyline points="192 160 192 64 96 64" fill="none" stroke="currentColor"
                                              stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="16"></polyline>
                                    <line x1="192" y1="232" x2="192" y2="192" fill="none" stroke="currentColor"
                                          stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                                </svg>
                            </x-chief::icon-button>
                        </div>
                    </div>

                    <div>
                        <button wire:click.prevent="submit" type="submit" class="btn btn-primary">Bestand opslaan
                        </button>
                    </div>

                </div>
            </form>

        </div>
    @endif

    @push('custom-scripts')
        <script src="https://unpkg.com/cropperjs@next"></script>
        <script>
            window.Livewire.on('imageCropOpened', fileId => {
                const cropper = new Cropper.default('#image_crop_' + fileId, {});
                console.log(cropper);

                const selection = {x: 30, y: 50, width: 20, height: 50};
                // trigger livewire model
                window.Livewire.dispatch('imageCropped', selection);

            })
        </script>
    @endpush
</div>
