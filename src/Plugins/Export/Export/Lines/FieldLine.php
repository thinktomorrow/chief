<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export\Lines;

class FieldLine implements Line
{
    const NON_LOCALIZED = 'x';

    protected string $encryptedReference;
    protected string $modelReference;
    protected string $fieldKey;
    protected string $resourceLabel;
    protected string $modelLabel;
    protected string $fieldLabel;
    protected array $values;

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
            $this->getRemarks(),
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
        if ($locale) {
            return $this->values[$locale] ?? null;
        }

        // x marks a non localized value
        return $this->values[static::NON_LOCALIZED] ?? null;
    }

    public function toArray()
    {
        return $this->getColumns();
    }

    public function getRemarks(): string
    {
        $remarks = [];

        foreach ($this->values as $value) {
            if (strip_tags($value) !== $value) {
                $remarks[] = 'html';
            }

            // Check if it contains a href attribute
            if (preg_match('/href=/', $value)) {
                $remarks[] = 'link';
            }

            // Check if it contains a placeholder like :name
            if (preg_match('/\:\w+/', $value)) {
                $remarks[] = 'placeholder';
            }
        }

        return implode(', ', array_unique($remarks));
    }
}
