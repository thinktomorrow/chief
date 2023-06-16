<div>

    <livewire:chief-wire::file-upload
        :model-reference="$getModel()?->modelReference()->get()"
        :field-key="$field->getKey()"
        :field-name="$field->getName($locale)"
        :locale="$locale"
        :allow-multiple="$field->allowMultiple()"
        :assets="$field->getValue($locale)"
        :components="$field->getComponents()"
        :rules="$field->getRules()"
        :validation-messages="$field->getValidationMessages()"
        :validation-attribute="$field->getValidationAttribute()"
        :accepted-mime-types="$field->getAcceptedMimeTypes()"
    />
</div>
