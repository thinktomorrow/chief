<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export\Lines;

class FieldLine implements Line
{
    private string $encryptedReference;
    private string $modelReference;
    private string $fieldKey;
    private string $resourceLabel;
    private string $modelLabel;
    private string $fieldLabel;
    private array $values;

    public function __construct(string $modelReference, string $fieldKey, string $resourceLabel, string $modelLabel, string $fieldLabel, array $values)
    {
        $this->modelReference = $modelReference;
        $this->fieldKey = $fieldKey;
        $this->resourceLabel = $resourceLabel;
        $this->modelLabel = $modelLabel;
        $this->fieldLabel = $fieldLabel;
        $this->values = $values;

        $this->encryptedReference = encrypt($this->modelReference .'|'. $this->fieldKey);
    }

    public function getColumns(): array
    {
        return [
            $this->getReference(),
            $this->resourceLabel,
            $this->modelLabel,
            $this->fieldLabel,
            ...array_values($this->values),
        ];
    }

    public function getReference(): string
    {
        return $this->encryptedReference;
    }

    public function getResourceLabel(): string
    {
        return $this->resourceLabel;
    }

    public function getModelLabel(): string
    {
        return $this->modelLabel;
    }

    public function getFieldLabel(): string
    {
        return $this->fieldLabel;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getValue(?string $locale = null): ?string
    {
        if($locale) {
            return $this->values[$locale] ?? null;
        }

        // If not localized, return the first value
        return reset($this->values);
    }

    public function toArray()
    {
        return $this->getColumns();
    }
}
