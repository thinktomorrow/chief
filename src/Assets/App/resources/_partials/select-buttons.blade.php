<div class="flex flex-wrap gap-1.5 mt-3">
    @if($allowToUploadFiles())
        <label for="{{ $getFieldId() }}" class="cursor-pointer">
            <input
                type="file"
                id="{{ $getFieldId() }}"
                {{ $allowMultiple() ? 'multiple' : '' }}
                accept="{{ $acceptedMimeTypes() ?: '' }}"
                x-on:change="() => { uploadFiles([...$el.files]) }"
                class="hidden"
            />

            <x-chief::button>
                <svg><use xlink:href="#icon-upload"></use></svg>
                @if($allowMultiple()) Upload een nieuw bestand @else Upload een ander bestand @endif
            </x-chief::button>
        </label>
    @endif

    @if($allowToChooseFiles())
        <button wire:click="openFilesChoose" type="button">
            <x-chief::button>
                <svg><use xlink:href="#icon-plus"></use></svg>
                Kies uit de mediabibliotheek
            </x-chief::button>
        </button>
    @endif

    @if($allowToChooseExternalFiles())
        <button wire:click="openFilesChooseExternal" type="button">
            <x-chief::button>
                <svg><use xlink:href="#icon-link"></use></svg>
                Link een extern bestand
            </x-chief::button>
        </button>
    @endif
</div>
