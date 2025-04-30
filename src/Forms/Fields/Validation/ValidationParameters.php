<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation;

use Thinktomorrow\Chief\Forms\Fields\FieldName\FieldName;
use Thinktomorrow\Chief\Forms\Fields\FieldName\FieldNameHelpers;
use Thinktomorrow\Chief\Forms\Fields\Locales\LocalizedField;
use Thinktomorrow\Chief\Sites\ChiefSites;

class ValidationParameters
{
    private Validatable&LocalizedField $source;

    private array $locales = [];

    private bool $multiple = false;

    private \Closure $mapKeysCallback;

    final private function __construct(Validatable&LocalizedField $source)
    {
        $this->source = $source;

        if ($source->hasLocales()) {
            $this->locales = $source->getLocales();
        }

        $this->mapKeysCallback = fn ($key) => $key;
    }

    public static function make(Validatable&LocalizedField $source): self
    {
        return new static($source);
    }

    public function multiple(bool $multiple = true): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * The rules array prepared for the Validation object. In case of a
     * localized field, a rule row will be created per locale key.
     */
    public function getRules(): array
    {
        return $this->createEntryForEachLocale($this->source->getRules(), $this->locales);
    }

    public function getAttributes(): array
    {
        if (! $attribute = $this->source->getValidationAttribute()) {
            $attribute = $this->source->getLabel() ? $this->source->getLabel() : $this->source->getRawName();
            $attribute = (count($this->locales) && count($this->source->getLocales()) > 1) ? ':locale '.$attribute : $attribute;
        }

        $localeNames = array_map(fn ($locale) => ChiefSites::shortName($locale), $this->locales);

        return $this->createEntryForEachLocale($attribute, $localeNames);
    }

    public function getMessages(): array
    {
        $localeNames = array_map(fn ($locale) => ChiefSites::shortName($locale), $this->locales);

        return $this->createEntryForEachLocale($this->source->getValidationMessages(), $localeNames);
    }

    private function createEntryForEachLocale(string|array $value, array $localeReplacements): array
    {
        if (is_array($value)) {
            if (count($value) < 1) {
                return [];
            }

            // Als het al gestructureerd is als ["field.rule" => "..."], laat het dan staan
            if ($this->isAlreadyKeyed($value) && strpos(key($value), '.') !== false) {
                return $value;
            }

            // per-locale messages - elke regel per locale dupliceren
            if ($this->isAlreadyKeyed($value)) {
                $results = [];

                foreach ($this->locales as $locale) {
                    $fieldKey = call_user_func($this->mapKeysCallback, FieldNameHelpers::replaceBracketsByDots($this->source->getName()).'.'.$locale);

                    foreach ($value as $rule => $message) {
                        $replacedMessage = str_replace(':locale', $locale, $message);
                        $results["{$fieldKey}.{$rule}"] = $replacedMessage;
                    }
                }

                return $results;
            }

        }

        // standaard key gebruiken
        if (! count($this->locales)) {
            return [
                call_user_func($this->mapKeysCallback, FieldNameHelpers::replaceBracketsByDots($this->source->getName())) => $value,
            ];
        }

        $keys = $this->source->getFieldName()
            ->dotted()
            ->matrix($this->source->getRawName(), $this->locales);

        if ($this->multiple) {
            foreach ($keys as $i => $key) {
                $keys[$i] = $key.'.*';
            }
        }

        foreach ($keys as $i => $key) {
            $keys[$i] = call_user_func($this->mapKeysCallback, $key);
        }

        return is_array($value)
            ? array_fill_keys($keys, $value)
            : array_combine(
                $keys,
                FieldName::make()->template(':name')->matrix($value, $localeReplacements)
            );
    }

    public function mapKeys(\Closure $callback): self
    {
        $this->mapKeysCallback = $callback;

        return $this;
    }

    private function isAlreadyKeyed(array $value): bool
    {
        return ! array_is_list($value);
    }
}
