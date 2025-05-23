@php
    use Thinktomorrow\Chief\Plugins\ChiefPluginSections;
@endphp

<x-chief-assets::upload-and-dropzone>
    {{ $this->filePreview }}

    @error('files.0')
        <x-chief::callout size="small" variant="red" class="mt-2">
            {{ ucfirst($message) }}
        </x-chief::callout>
    @enderror

    {{ $this->fileSelect }}

    <template x-teleport="body">
        <livewire:chief-wire::file-field-choose
            parent-id="{{ $this->getId() }}"
            allowMultiple="{{ $allowMultiple }}"
        />
    </template>

    <template x-teleport="body">
        <livewire:chief-wire::file-field-choose-external
            parent-id="{{ $this->getId() }}"
            allowMultiple="{{ $allowMultiple }}"
        />
    </template>

    <template x-teleport="body">
        <livewire:chief-wire::file-field-edit
            :parent-id="$this->getId()"
            :model-reference="$modelReference"
            :field-key="$fieldKey"
            :locale="$locale"
            :components="$this->components"
        />
    </template>

    @foreach (app(ChiefPluginSections::class)->getLivewireFileComponents() as $livewireFileComponent)
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
