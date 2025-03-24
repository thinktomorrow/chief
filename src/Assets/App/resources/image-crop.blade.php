<div
    x-cloak
    x-data="{ open: @entangle('isOpen') }"
    x-show="open"
    class="fixed inset-0 z-[100] flex items-center justify-center"
>
    @if ($isOpen)
        <div class="absolute inset-0 bg-black/50"></div>

        <div class="relative rounded-xl bg-white p-12">
            <button class="btn btn-primary-outline" type="button" x-on:click="close()">X</button>

            <h1>CROPPING</h1>

            <!-- form prevents enter key in fields in this modal context to trigger submits of other form on the page -->
            <form>
                <div class="flex items-start gap-12">
                    <div class="shrink-0 space-y-6">
                        <div class="h-80 w-80 overflow-hidden rounded-xl bg-grey-100">
                            <img
                                id="image_crop_{{ $previewFile->id }}"
                                src="{{ $previewFile->previewUrl }}"
                                class="h-full w-full object-contain"
                            />
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <x-chief::button variant="grey" size="sm">
                                <x-chief::icon.crop />
                            </x-chief::button>
                        </div>
                    </div>

                    <div>
                        <button wire:click.prevent="submit" type="submit" class="btn btn-primary">
                            Bestand opslaan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    @push('custom-scripts')
        <script src="https://unpkg.com/cropperjs@next"></script>
        <script>
            window.Livewire.on('imageCropOpened', (fileId) => {
                const cropper = new Cropper.default('#image_crop_' + fileId, {});

                const selection = { x: 30, y: 50, width: 20, height: 50 };
                // trigger livewire model
                window.Livewire.dispatch('imageCropped', selection);
            });
        </script>
    @endpush
</div>
