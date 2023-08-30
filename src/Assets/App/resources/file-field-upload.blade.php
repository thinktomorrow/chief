<x-chief-assets::upload-and-dropzone>
    {{ $this->filePreview }}

    @error('files.0')
        <x-chief::inline-notification type="error" class="mt-2">
            {{ ucfirst($message) }}
        </x-chief::inline-notification>
    @enderror

    {{ $this->fileSelect }}

    <div>
        <livewire:chief-wire::file-field-choose
            parent-id="{{ $this->id }}"
            allowMultiple="{{ $allowMultiple }}"
        />
    </div>

    <div>
        <livewire:chief-wire::file-field-choose-external
            parent-id="{{ $this->id }}"
            allowMultiple="{{ $allowMultiple }}"
        />
    </div>

    <div>
        <livewire:chief-wire::file-field-edit
            parent-id="{{ $this->id }}"
            model-reference="{{ $modelReference }}"
            field-key="{{ $fieldKey }}"
            locale="{{ $locale }}"
            :components="$this->components"
        />
    </div>

    @foreach(app(\Thinktomorrow\Chief\Plugins\ChiefPluginSections::class)->getLivewireFileComponents() as $livewireFileComponent)
        <div>
            <livewire:is
                component="{{ $livewireFileComponent }}"
                parent-id="{{ $this->id }}"
                model-reference="{{ $modelReference }}"
                field-key="{{ $fieldKey }}"
                locale="{{ $locale }}"
            />
        </div>
    @endforeach
</x-chief-assets::upload-and-dropzone>
