<x-chief::dialog.modal wired>
    @if ($isOpen)
        <!-- form prevents enter key in fields in this modal context to trigger submits of other form on the page -->
        <form class="flex gap-8">
            <div class="shrink-0 space-y-6">
                @if ($previewFile->isImage())
                    <div class="h-80 w-80 overflow-hidden rounded-xl bg-grey-100">
                        <img src="{{ $previewFile->previewUrl }}" class="h-full w-full object-contain" />
                    </div>
                @endif
            </div>

            <div class="grow">
                <x-chief::button wire:click.prevent="submit" variant="blue">Opslaan</x-chief::button>
            </div>
        </form>
    @endif
</x-chief::dialog.modal>
