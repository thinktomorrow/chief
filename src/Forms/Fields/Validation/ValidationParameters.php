<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation;

use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;
use Thinktomorrow\Chief\Forms\Fields\Common\Localizable;
use Thinktomorrow\Chief\Forms\Fields\Common\LocalizedFormKey;

class ValidationParameters
{
    private Validatable & Localizable $source;

    final private function __construct(Validatable & Localizable $source)
    {
        $this->source = $source;
    }

    public static function make(Validatable & Localizable $source): self
    {
        return new static($source);
    }

    /**
     * The rules array prepared for the Validation object. In case of a
     * localized field, a rule row will be created per locale key.
     */
    public function getRules(): array
    {
        return $this->createEntryForEachLocale($this->source->getRules());
    }

    public function getAttributes(): array
    {
        if (! $attribute = $this->source->getValidationAttribute()) {
            $attribute = $this->source->getLabel() ? $this->source->getLabel() : $this->source->getName();
            $attribute .= ($this->source->hasLocales() && count($this->source->getLocales()) > 1) ? ' :locale' : '';
        }

        return $this->createEntryForEachLocale($attribute);
    }

    public function getMessages(): array
    {
        return $this->createEntryForEachLocale($this->source->getValidationMessages());
    }

    private function createEntryForEachLocale(string|array $value): array
    {
        if (is_array($value)) {
            if (count($value) < 1) {
                return [];
            }

            // If the array already has a associative key, we assume this is a custom validation entry, so we leave it be
            if ($this->isAlreadyKeyed($value)) {
                return $value;
            }
        }

        if (! $this->source->hasLocales()) {
            return [FormKey::replaceBracketsByDots($this->source->getName()) => $value];
        }

        $keys = $this->source->getLocalizedFormKey()
            ->dotted()
            ->matrix($this->source->getName(), $this->source->getLocales());

        return is_array($value)
            ? array_fill_keys($keys, $value)
            : array_combine(
                $keys,
                LocalizedFormKey::make()->template(':name')->matrix($value, array_map(fn ($locale) => strtoupper($locale), $this->source->getLocales()))
            );
    }

    private function isAlreadyKeyed(array $value): bool
    {
        return ! array_is_list($value) && is_array(reset($value));
    }
}
