<?php

namespace Thinktomorrow\Chief\Plugins\TranslationsExport\Document;

class TranslationLine implements Line
{
    private string $reference;
    private string $label;
    private ?string $originalValue;
    private array $targetValues;

    public function __construct(string $reference, string $label, ?string $originalValue, array $targetValues)
    {
        $this->reference = $reference;
        $this->label = $label;
        $this->originalValue = $originalValue;
        $this->targetValues = $targetValues;
    }

    public static function makeLabel(string $label)
    {
        return new static('', $label, null, []);
    }

    public function getEncryptedReference(): string
    {
        return encrypt($this->reference);
    }

//    public function getReference(): string
//    {
//        return $this->reference;
//    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getOriginalValue(): ?string
    {
        return $this->originalValue;
    }

    public function getTargetValue(string $locale): ?string
    {
        return $this->targetValues[$locale] ?? null;
    }

    public function getColumns(): array
    {
        // TODO: Implement getColumns() method.
    }
}
