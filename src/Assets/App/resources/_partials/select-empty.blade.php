<div
    class="flex flex-wrap items-center justify-center gap-3 rounded-lg border border-dashed border-grey-200 bg-white p-5 shadow-sm"
>
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

            <x-chief::button for="{{ $getFieldId() }}" variant="outline-white" size="sm">
                <x-chief::icon.upload />
                <span>Upload een nieuw bestand</span>
            </x-chief::button>
        </div>
    @endif

    @if ($allowToChooseFiles())
        <x-chief::button wire:click="openFilesChoose" variant="outline-white" size="sm">
            <x-chief::icon.plus-sign />
            <span>Kies uit de mediabibliotheek</span>
        </x-chief::button>
    @endif

    @if ($allowToChooseExternalFiles())
        <x-chief::button wire:click="openFilesChooseExternal" variant="outline-white" size="sm">
            <x-chief::icon.link />
            <span>Link een extern bestand</span>
        </x-chief::button>
    @endif
</div>
