<?php

namespace Thinktomorrow\Chief\Fields;

class LocalizedFieldValidationRules
{
    /** @var array */
    private $locales;

    /** @var string */
    private $defaultLocale;

    public function __construct(array $locales = [])
    {
        $this->locales = $locales;
        $this->defaultLocale = config('app.fallback_locale');
    }

    public function rules($rules): array
    {
        $localizedRules = [];

        foreach ($rules as $attr => $rule) {
            foreach ($this->locales as $locale) {

                // If it contains an asterisk, w'll replace that, else by default prepend the name with the
                // expected trans.<locale>. string
                $localizedAttr = (false !== strpos($attr, '*'))
                    ? str_replace('*', $locale, $attr)
                    : 'trans.' . $locale . '.' . $attr;

                $localizedRules[$localizedAttr] = $rule;
            }
        }

        $rules = $localizedRules;

        return $rules;
    }

    /**
     * Request payload can influence the validation rules. If an entire locale input
     * is empty, this locale should be completely ignored unless its the default
     *
     * @param array $data
     * @return LocalizedFieldValidationRules
     */
    public function influenceByPayload(array $data)
    {
        if (! isset($data['trans'])) {
            return $this;
        }

        // Remove locales that are considered empty in the request payload
        foreach ($data['trans'] as $locale => $values) {
            if ($locale == $this->defaultLocale || ! is_array_empty($values)) {
                continue;
            }

            $key = array_search($locale, $this->locales);
            unset($this->locales[$key]);
        }

        return $this;
    }
}
