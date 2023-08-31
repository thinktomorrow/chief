@php use Thinktomorrow\Chief\Plugins\ChiefPluginSections; @endphp
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
            parent-id="{{ $this->getId() }}"
            allowMultiple="{{ $allowMultiple }}"
        />
    </div>

    <div>
        <livewire:chief-wire::file-field-choose-external
            parent-id="{{ $this->getId() }}"
            allowMultiple="{{ $allowMultiple }}"
        />
    </div>

    <div>
        <livewire:chief-wire::file-field-edit
            parent-id="{{ $this->getId() }}"
            model-reference="{{ $modelReference }}"
            field-key="{{ $fieldKey }}"
            locale="{{ $locale }}"
            :components="$this->components"
        />
    </div>

    @foreach(app(ChiefPluginSections::class)->getLivewireFileComponents() as $livewireFileComponent)
        <div>
            <livewire:is
                component="{{ $livewireFileComponent }}"
                parent-id="{{ $this->getId() }}"
                model-reference="{{ $modelReference }}"
                field-key="{{ $fieldKey }}"
                locale="{{ $locale }}"
            />
        </div>
    @endforeach
</x-chief-assets::upload-and-dropzone>
