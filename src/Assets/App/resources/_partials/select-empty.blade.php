<div class="flex flex-wrap items-center justify-center gap-3 p-5 border border-dashed rounded-lg shadow-sm border-grey-200">
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
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /> </svg>
            Upload een nieuw bestand
        </x-chief::button>
    </label>

    @if($allowToChooseFiles())
        <button type="button" wire:click="openFilesChoose">
            <x-chief::button>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /> </svg>
                Kies uit de mediabibliotheek
            </x-chief::button>
        </button>
    @endif

    @if($allowToChooseExternalFiles())
        <button wire:click="openFilesChooseExternal" type="button">
            <x-chief::button>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /> </svg>
                Link een extern bestand
            </x-chief::button>
        </button>
    @endif
</div>
