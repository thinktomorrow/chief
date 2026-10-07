@php
    // Assets always expect a locale. We enforce this even when locales are missing
    use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
    use Thinktomorrow\Chief\Sites\ChiefSites;

    $locale ??= ChiefSites::primaryLocale();

    // Check if component is used inside a parent Livewire component (such as AddFragment)
    $insideComponent = isset($this) && method_exists($this, 'getId');

    if ($insideComponent) {
        $currentPreviewFiles = data_get($this->form, LivewireFieldName::get($getName($locale ?? null), null));
    } else {
        $currentPreviewFiles = array_map(
            fn ($file) => $file instanceof PreviewFile ? $file : PreviewFile::fromAsset($file),
            $field->getValue($locale),
        );
    }
@endphp

<div data-slot="control">
    <livewire:chief-wire::file-field-upload
        wire:key="{{ $getWireModelValue($locale ?? null) }}"
        wire:model="{{ $getWireModelValue($locale ?? null) }}"
        parentComponentId="{{ $insideComponent ? $this->getId() : null }}"
        :modelReference="$getModel()?->modelReference()->get()"
        :fieldKey="$field->getKey()"
        :locale="$locale"
        :fieldName="$field->getName($locale)"
        :allowMultiple="$field->allowMultiple()"
        :previewFiles="$currentPreviewFiles"
        :components="$field->getComponents()"
        :rules="$field->getRules()"
        :validationMessages="$field->getValidationMessages()"
        :validationAttribute="$field->getValidationAttribute()"
        :acceptedMimeTypes="$field->getAcceptedMimeTypes()"
        :allowExternalFiles="$field->getAllowExternalFiles()"
        :allowLocalFiles="$field->getAllowLocalFiles()"
        :assetType="$field->getAssetType() ?? 'default'"
    />
</div>
