<div class="mt-3 flex flex-wrap gap-1.5">
    @if ($allowToUploadFiles())
        <div>
            <input
                type="file"
                id="{{ $getFieldId() }}"
                {{ $allowMultiple() ? 'multiple' : '' }}
                accept="{{ $acceptedMimeTypes() ?: '' }}"
                x-on:change="
                    () => {
                        uploadFiles([...$el.files])
                    }
                "
                class="hidden"
            />

            <x-chief::button for="{{ $getFieldId() }}" size="sm" variant="grey">
                <x-chief::icon.upload />
                <span>
                    @if ($allowMultiple())
                        Upload een nieuw bestand
                    @else
                        Upload een ander bestand
                    @endif
                </span>
            </x-chief::button>
        </div>
    @endif

    @if ($allowToChooseFiles())
        <x-chief::button wire:click="openFilesChoose" size="sm" variant="grey">
            <x-chief::icon.plus-sign />
            <span>Kies uit de mediabibliotheek</span>
        </x-chief::button>
    @endif

    @if ($allowToChooseExternalFiles())
        <x-chief::button wire:click="openFilesChooseExternal" size="sm" variant="grey">
            <x-chief::icon.link />
            <span>Link een extern bestand</span>
        </x-chief::button>
    @endif
</div>
