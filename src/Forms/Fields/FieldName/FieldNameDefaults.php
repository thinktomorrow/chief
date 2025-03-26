<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\FieldName;

trait FieldNameDefaults
{
    protected ?string $fieldNameTemplate = null;

    public function getFieldName(): FieldName
    {
        return FieldName::make()
            ->bracketed()
            ->template(str_contains($this->name, ':locale') ? ':name' : $this->getFieldNameTemplate());
    }

    public function getFieldNameTemplate(): string
    {
        if (! $this->fieldNameTemplate) {
            return FieldName::getDefaultTemplate();
        }

        return $this->fieldNameTemplate;
    }

    public function setFieldNameTemplate(string $fieldNameTemplate): static
    {
        $this->fieldNameTemplate = $fieldNameTemplate;

        return $this;
    }
}
