<?php

namespace Thinktomorrow\Chief\Plugins\TranslationsExport\Document;

class TranslationLine implements Line
{
    private string $modelReference;
    private string $fieldKey;
    private string $modelLabel;
    private string $fieldLabel;
    private ?string $originalValue;
    private array $targetValues;

    public function __construct(string $modelReference, string $fieldKey, string $modelLabel, string $fieldLabel, ?string $originalValue, array $targetValues)
    {
        $this->modelReference = $modelReference;
        $this->fieldKey = $fieldKey;
        $this->modelLabel = $modelLabel;
        $this->fieldLabel = $fieldLabel;
        $this->originalValue = $originalValue;
        $this->targetValues = $targetValues;
    }

//    public static function makeLabel(string $label)
//    {
//        return new static('', $label, null, []);
//    }

    public function getColumns(): array
    {
        return [
            '',
            $this->getReference(),
            $this->modelLabel,
            $this->fieldLabel,
            $this->originalValue,
            ...$this->targetValues,
        ];
    }

    public function getReference(): string
    {
//        return $this->modelReference .'|'. $this->fieldKey;
        return encrypt($this->modelReference .'|'. $this->fieldKey);
    }

    private function getLabel(): string
    {
        return $this->fieldLabel;
    }

    private function getOriginalValue(): ?string
    {
        return $this->originalValue;
    }

    private function getTargetValue(string $locale): ?string
    {
        return $this->targetValues[$locale] ?? null;
    }

    public function toArray()
    {
        return $this->getColumns();
    }
}
