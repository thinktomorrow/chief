<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Validation;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class ValidationNames
{
    /** @var array */
    private $filters = [
        'replacePlaceholders',
        'removeEmptyTranslations',
        'removeEmptyLocalizedFileEntries',
        'removeKeysToBeRemoved',
    ];

    /** @var string */
    private $format;

    /** @var array */
    private $placeholders;

    /** @var array */
    private $payload;

    /** @var array */
    private $keysToBeRemoved;

    /** @var string */
    private $requiredLocale;

    final private function __construct(string $format)
    {
        $this->format = $format;
        $this->placeholders = $this->payload = $this->keysToBeRemoved = [];

        $this->requiredLocale((string)config('app.fallback_locale', 'nl'));
    }

    public static function fromFormat(string $format)
    {
        return new static($format);
    }

    public function get(): array
    {
        $names = [$this->format];

        foreach ($this->filters as $filter) {
            $names = call_user_func_array([$this, $filter], [$names]);
        }

        return array_values($names);
    }

    public function replace($placeholder, array $replacements): self
    {
        $this->placeholders[$placeholder] = $replacements;

        return $this;
    }

    public function payload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function requiredLocale(string $requiredLocale): self
    {
        $this->requiredLocale = $requiredLocale;

        return $this;
    }

    public function removeKeysContaining(array $keysToBeRemoved): self
    {
        $this->keysToBeRemoved = array_merge($this->keysToBeRemoved, $keysToBeRemoved);

        return $this;
    }

    private function replacePlaceholders(array $keys): array
    {
        foreach ($this->placeholders as $placeholder => $replacements) {
            $newKeySet = [];
            foreach ($keys as $i => $key) {
                if (count($replacements) < 1) {
                    $newKeySet[] = $key;
                    continue;
                }

                foreach ($replacements as $replacement) {
                    $newKeySet[] = str_replace(':' . $placeholder, $replacement, $key);
                }
            }

            $keys = $newKeySet;
        }

        return $keys;
    }

    private function removeKeysToBeRemoved(array $keys): array
    {
        $filteredKeys = $keys;

        foreach ($this->keysToBeRemoved as $keyToBeRemoved) {
            $pattern = preg_quote($keyToBeRemoved, '#');

            /* Any asterix which work as an wildcard of characters */
            if (false !== strpos($pattern, '*')) {
                $pattern = str_replace('\*', '(.+)', $pattern);
            }

            foreach ($filteredKeys as $k => $filteredKey) {
                if (preg_match("#$pattern#", $filteredKey)) {
                    unset($filteredKeys[$k]);
                }
            }
        }

        return $filteredKeys;
    }

    private function removeEmptyTranslations(array $keys): array
    {
        if (!isset($this->payload['trans'])) {
            return $keys;
        }

        $filteredKeys = $keys;

        // Remove locales that are considered empty in the request payload
        foreach ($this->payload['trans'] as $locale => $values) {
            if ($locale == $this->requiredLocale || !is_array_empty($values)) {
                continue;
            }

            // Remove all 'trans' entries for this locale
            foreach ($filteredKeys as $i => $key) {
                if (Str::startsWith($key, 'trans.' . $locale)) {
                    unset($filteredKeys[$i]);
                }
            }
        }

        return $filteredKeys;
    }

    private function removeEmptyLocalizedFileEntries(array $keys): array
    {
        $filteredKeys = $keys;

        foreach ($filteredKeys as $i => $key) {
            if (!Str::startsWith($key, ['images.', 'files.'])) {
                continue;
            }

            $payload = Arr::get($this->payload, $key, '_notfound_');

            // If the payload is empty and this is not the entry for the required locale
            if ($payload !== '_notfound_' && !$payload && !Str::endsWith($key, '.' . $this->requiredLocale)) {
                unset($filteredKeys[$i]);
            }
        }

        return $filteredKeys;
    }
}
