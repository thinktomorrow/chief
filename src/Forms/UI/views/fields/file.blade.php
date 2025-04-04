@php
    // Assets always expect a locale. We enforce this even when locales are missing
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
    use Thinktomorrow\Chief\Sites\ChiefSites;

    $locale ??= ChiefSites::primaryLocale();

    // Check if component is used inside a parent Livewire component (such as AddFragment)
    $insideComponent = isset($this) && method_exists($this, 'getId');
@endphp

<div data-slot="control">
    <livewire:chief-wire::file-field-upload
        wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
        parent-component-id="{{ $insideComponent ? $this->getId() : null }}"
        :model-reference="$getModel()?->modelReference()->get()"
        :field-key="$field->getKey()"
        :locale="$locale"
        :field-name="$field->getName($locale)"
        :allow-multiple="$field->allowMultiple()"
        :assets="$field->getValue($locale)"
        :components="$field->getComponents()"
        :rules="$field->getRules()"
        :validation-messages="$field->getValidationMessages()"
        :validation-attribute="$field->getValidationAttribute()"
        :accepted-mime-types="$field->getAcceptedMimeTypes()"
        :allow-external-files="$field->getAllowExternalFiles()"
        :allow-local-files="$field->getAllowLocalFiles()"
        :asset-type="$field->getAssetType() ?? 'default'"
    />
</div>
