<?php

namespace Thinktomorrow\Chief\Concerns\Translatable;

use InvalidArgumentException;

/**
 * Trait Translatable
 * @author Ben Cavens
 *
 * Allows the entity to contain multiple translations
 * requires the parent entity to include the Dimsav/Translatable/Translatable trait
 *
 */
trait Translatable
{
    public function getDefaultTranslation($attribute)
    {
        if (!($translation = $this->getTranslation(config('app.fallback_locale')))) {
            return null;
        }

        return $translation->$attribute;
    }

    /**
     * Save multiple attributes at once
     *
     * @param $locale
     * @param array $values
     */
    public function updateTranslation($locale, array $values)
    {
        foreach ($values as $attribute => $value) {
            $this->setTranslation($locale, $attribute, $value);
        }

        $this->save();
    }

    /**
     * Save a single attribute
     *
     * @param $locale
     * @param $attribute
     * @param $value
     */
    public function saveTranslation($locale, $attribute, $value)
    {
        $this->setTranslation($locale, $attribute, $value);

        $this->save();
    }

    /**
     * Get translation for a specific column
     *
     * @param $attribute
     * @param $locale
     * @param bool $strict false = use fallback locale, true = no result if locale not present
     * @return null
     */
    public function getTranslationFor($attribute, $locale = null, $strict = true)
    {
        // No locale given means we take the current defaulted locale (handled automagically)
        if (!$locale) {
            return $this->getAttribute($attribute);
        }

        if (!$this->hasTranslation($locale) && $strict) {
            return null;
        }

        return $this->getTranslation($locale)->{$attribute};
    }

    /**
     * Create or update a translation attribute.
     * Note: only sets to entity, does not save it.
     *
     * @param $locale
     * @param $attribute
     * @param $value
     */
    private function setTranslation($locale, $attribute, $value)
    {
        $this->validateLocale($locale);

        $this->translateOrNew($locale)->$attribute = $value;
    }

    public function removeTranslation($locale)
    {
        if (!$this->hasTranslation($locale)) {
            return;
        }

        return $this->getTranslation($locale)->delete();
    }

    public static function availableLocales()
    {
        // This is a method expected from the dimsav package
        return (new static())->getLocales();
    }

    /**
     * @deprecated use availableLocales instead
     * @return \Illuminate\Config\Repository|mixed
     */
    public static function getAvailableLocales()
    {
        return static::availableLocales();
    }

    /**
     * Get all locales where this entity
     * already has any translations of
     *
     * @return array
     */
    public function getUsedLocales()
    {
        return $this->fetchLocales(true);
    }

    /**
     * Get all available locales where this entity
     * does not have any translations of
     *
     * @return array
     */
    public function getNonUsedLocales()
    {
        return $this->fetchLocales(false);
    }

    /**
     * Get all locales associated with this entity
     *
     * @param bool $available
     * @return array
     */
    private function fetchLocales($available = true)
    {
        $available_locales = static::availableLocales();
        $current_locales = $this->translations->pluck('locale')->toArray();

        return array_filter($available_locales, function ($v) use ($current_locales, $available) {
            return $available ? in_array($v, $current_locales) : !in_array($v, $current_locales);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Is passed locale one of the allowed ones from config?
     *
     * @param $locale
     */
    private function validateLocale($locale)
    {
        if (!in_array($locale, static::availableLocales())) {
            throw new InvalidArgumentException('Locale [' . $locale . '] is not available');
        }
    }

    /**
     * Dimsav translatable trait overrides the toArray in order to
     * inject default translations. To ignore this behaviour and
     * present the actual values you should use this method.
     *
     * @return array
     */
    public function toRawArray()
    {
        return parent::toArray();
    }

    /**
     * Checks how many locales we have configured.
     * We use this check to prevent showing of stuff like tabs when we only have 1 locale set up.
     *
     * @return bool
     */
    public function hasMultipleApplicationLocales()
    {
        return count(static::availableLocales()) > 1 ?: false;
    }

    /**
     * Set a trans array on this model for use in crud forms.
     * This is used for old input.
     */
    public function injectTranslationForForm()
    {
        // Make all translations available for our form
        $trans = [];
        foreach ($this->getUsedLocales() as $locale) {
            $trans[$locale] = $this->getTranslation($locale)->toArray();
        }
        $this->trans = $trans;
    }

    /**
     * Retrieve translation value from the injected translations for the form
     * Note that this is only valid if the injectTranslationForForm() method
     * is called prior to calling this method.
     *
     * @param $locale
     * @param $key
     * @return string|null
     */
    public function translateForForm($locale, $key)
    {
        if (!isset($this->trans) || !isset($this->trans[$locale])) {
            return null;
        }

        return $this->trans[$locale][$key] ?? null;
    }

    /**
     * Update or create translatable fields for a translatable entity
     *
     * @param $translations
     * @param TranslatableContract $entity
     * @param array $keys pass the columns that need to be translated. these need to match the passed request keys
     */
    protected function persistTranslations($translations, TranslatableContract $entity, array $keys)
    {
        foreach ($entity->getAvailableLocales() as $available_locale) {
            // Remove the product translation if any already exists
            // Translation is also removed if all fields of a translation are left empty
            if (!isset($translations[$available_locale]) or !($translation = $translations[$available_locale]) or $this->isCompletelyEmpty($keys, $translation)) {
                $entity->removeTranslation($available_locale);
                continue;
            }
            $this->persistTranslation($entity, $keys, $translation, $available_locale);
        }
    }
    /**
     * Check if certain locale input submission is left empty
     *
     * @param array $keys
     * @param $translation
     * @return array
     */
    protected function isCompletelyEmpty(array $keys, array $translation)
    {
        $is_completely_empty = true;
        foreach ($keys as $key) {
            if (!isset($translation[$key])) {
                continue;
            }
            if (trim($translation[$key])) {
                $is_completely_empty = false;
            }
        }
        return $is_completely_empty;
    }
    /**
     * @param TranslatableContract $entity
     * @param array $keys
     * @param $translation
     * @param $available_locale
     */
    protected function persistTranslation(TranslatableContract $entity, array $keys, array $translation, $available_locale)
    {
        $attributes = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $translation)) {
                $attributes[$key] = $translation[$key];
            }
        }
        $entity->updateTranslation($available_locale, $attributes);
    }
}
