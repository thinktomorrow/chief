<?php

namespace Thinktomorrow\Chief\Forms\Fields\Locales;


// Get all locales the content should be available in.
// These are the tabs shown for the fields in the admin.
// array of locales and per locale the locales that fallback to this root locale
// E.g. ['nl' => ['nl', 'en'], 'fr' => ['fr', 'fr-be']]

// If locale value is not present, we assume this locale value should fallback to the fallback locale value.
// If locale value is null or empty ('', null), we assume this value is explicitly set to empty and should not fallback.

/**
 * All the locales that the field values are available in.
 * This is used to determine the tabs shown in the admin for the field.
 */
class FieldLocales
{
    /** @var FieldLocaleGroup[] */
    public array $groups = [];

    public static function fromArray(array $locales): self
    {
        self::validateArray($locales);

        $fieldLocales = new static();

        foreach($locales as $fallbackLocale => $_locales) {
            foreach($_locales as $locale) {
                $fieldLocales->add($locale, $fallbackLocale);
            }
        }

        return $fieldLocales;
    }

    public function add(string $locale, ?string $fallbackLocale = null): self
    {
        $fallbackLocale ??= $locale;

        foreach($this->groups as $group) {
            if($group->fallbackLocale === $fallbackLocale) {
                $group->add($locale);
                return $this;
            }
        }

        $this->groups[] = new FieldLocaleGroup($fallbackLocale, [$locale]);

        return $this;
    }

    public static function validateArray(array $locales): void
    {
        foreach ($locales as $fallbackLocale => $_locales) {
            if (!is_string($fallbackLocale) || !is_array($_locales)) {
                throw new \InvalidArgumentException('Invalid locales array format. Expected array of locales and fallback locale as key.');
            }

            foreach($_locales as $_locale) {
                if (!is_string($_locale) || !$_locale) {
                    throw new \InvalidArgumentException('Locale value should be a non-empty string referring to a locale.');
                }
            }
        }
    }

    public function getLocales(): array
    {
        $locales = [];

        foreach($this->groups as $group) {
            $locales = array_merge($locales, $group->locales);
        }

        return $locales;
    }
}
