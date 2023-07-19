<div class="flex flex-wrap justify-center gap-3 mt-3">
    <label
        for="{{ $getFieldId() }}"
        class="relative flex gap-1.5 px-3 py-2 text-sm leading-5 rounded-full hover:bg-primary-50 bg-grey-100 body-dark hover:text-primary-500"
    >
        <input
            type="file"
            id="{{ $getFieldId() }}"
            {{ $allowMultiple() ? 'multiple' : '' }}
            accept="{{ $acceptedMimeTypes() ?: '' }}"
            x-on:change="() => { uploadFiles([...$el.files]) }"
            class="absolute inset-0 opacity-0 cursor-pointer"
        />

        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /> </svg>
        @if($allowMultiple())
            Upload een nieuw bestand
        @else
            Upload een ander bestand
        @endif
    </label>

    @if($allowToChooseFiles())
        <button
            wire:click="openFilesChoose"
            type="button"
            class="relative flex gap-1.5 px-3 py-2 text-sm leading-5 rounded-full hover:bg-primary-50 bg-grey-100 body-dark hover:text-primary-500"
        >
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /> </svg>
            Kies uit de mediabibliotheek
        </button>
    @endif

    @if($allowToChooseExternalFiles())
        <button
            wire:click="openFilesChooseExternal"
            type="button"
            class="relative flex gap-1.5 px-3 py-2 text-sm leading-5 rounded-full hover:bg-primary-50 bg-grey-100 body-dark hover:text-primary-500"
        >
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /> </svg>
            Link
        </button>
    @endif
</div>
