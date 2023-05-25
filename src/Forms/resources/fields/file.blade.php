<div>
    <livewire:chief-wire::file-upload
        :model-reference="$getModel()->modelReference()->get()"
        :field-key="$field->getKey()"
        :field-name="$field->getName($locale)"
        :locale="$locale"
        :allow-multiple="$field->allowMultiple()"
        :assets="$field->getValue($locale)"
        :components="$field->getComponents()"
        :accepted-mime-types="$field->getAcceptedMimeTypes()"
    />
</div>
