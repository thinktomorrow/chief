<div class="flex flex-wrap gap-1.5 mt-3">
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

        <svg class="w-5 h-5"><use xlink:href="#icon-upload"></use></svg>
        @if($allowMultiple()) Upload een nieuw bestand @else Upload een ander bestand @endif
    </label>

    @if($allowToChooseFiles())
        <button
            wire:click="openFilesChoose"
            type="button"
            class="relative flex gap-1.5 px-3 py-2 text-sm leading-5 rounded-full hover:bg-primary-50 bg-grey-100 body-dark hover:text-primary-500"
        >
            <svg class="w-5 h-5"><use xlink:href="#icon-plus"></use></svg>
            Kies uit de mediabibliotheek
        </button>
    @endif

    @if($allowToChooseExternalFiles())
        <button
            wire:click="openFilesChooseExternal"
            type="button"
            class="relative flex gap-1.5 px-3 py-2 text-sm leading-5 rounded-full hover:bg-primary-50 bg-grey-100 body-dark hover:text-primary-500"
        >
            <svg class="w-5 h-5"><use xlink:href="#icon-link"></use></svg>
            {{-- TODO: add driver type to label --}}
            {{-- {{ \Illuminate\Support\Arr::join(array_keys(\Thinktomorrow\Chief\Assets\App\ExternalFiles\DriverFactory::$map),',',' of ') }} link --}}
            Link een extern bestand
        </button>
    @endif
</div>
