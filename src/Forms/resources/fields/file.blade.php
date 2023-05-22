<div>
    <livewire:chief-wire::file-upload
        :field-name="$field->getName($locale)"
        :allow-multiple="$field->allowMultiple()"
        :assets="$field->getValue($locale)"
        :components="$field->getComponents()"
    />
</div>
