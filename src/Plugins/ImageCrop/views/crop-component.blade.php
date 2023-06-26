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

            </div>

            <div class="space-y-6 grow">
                <div>
                    <button wire:click.prevent="submit" type="submit" class="btn btn-primary">
                        Opslaan
                    </button>
                </div>
            </div>
        </form>
    @endif
</x-chief::dialog>
