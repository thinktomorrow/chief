<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Validation;

use Illuminate\Support\Str;

class ValidationNames
{
    /** @var array */
    private $filters = [
        'replacePlaceholders',
        'removeEmptyTranslations',
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
    private $defaultLocale;

    final private function __construct(string $format)
    {
        $this->format = $format;
        $this->placeholders = $this->payload = $this->keysToBeRemoved = [];

        $this->defaultLocale((string) config('app.fallback_locale', 'nl'));
    }

    public static function fromFormat(string $format)
    {
        return new static($format);
    }

    public function get(): array
    {
        $names = [$this->format];

        foreach($this->filters as $filter){
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

    public function defaultLocale(string $defaultLocale): self
    {
        $this->defaultLocale = $defaultLocale;

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

                if(count($replacements) < 1) {
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

        foreach($this->keysToBeRemoved as $keyToBeRemoved) {
            $pattern = preg_quote($keyToBeRemoved, '#');

            /* Any asterix which work as an wildcard of characters */
            if (false !== strpos($pattern, '*')) {
                $pattern = str_replace('\*', '(.+)', $pattern);
            }

            foreach($filteredKeys as $k => $filteredKey) {
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
            if ($locale == $this->defaultLocale || ! is_array_empty($values)) {
                continue;
            }

            // Remove all 'trans' entries for this locale
            foreach($filteredKeys as $i => $key){
                if(Str::startsWith($key, 'trans.'.$locale)) {
                    unset($filteredKeys[$i]);
                }
            }
        }

        return $filteredKeys;
    }
}
