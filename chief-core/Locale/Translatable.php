<?php

namespace Chief\Locale;

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
        if (!($translation = $this->getTranslation(config('app.fallback_locale'))))
        {
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
        foreach ($values as $attribute => $value)
        {
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
    public function getTranslationFor($attribute,$locale = null, $strict = true)
    {
        // No locale given means we take the current defaulted locale (handled automagically)
        if(!$locale) return $this->getAttribute($attribute);

        if (!$this->hasTranslation($locale) && $strict) return null;

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
        if (!$this->hasTranslation($locale))
        {
            return;
        }

        return $this->getTranslation($locale)->delete();
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
        $available_locales = self::getAvailableLocales();
        $current_locales = $this->translations()->lists('locale')->toArray();

        return array_filter($available_locales, function ($v) use ($current_locales, $available)
        {
            return $available ? in_array($v, $current_locales) : !in_array($v, $current_locales);
        }, ARRAY_FILTER_USE_BOTH);
    }

    public static function getAvailableLocales()
    {
        return config('translatable.locales');
    }

    /**
     * Is passed locale one of the allowed ones from config?
     *
     * @param $locale
     */
    private function validateLocale($locale)
    {
        if (!in_array($locale, config('translatable.locales',[])))
        {
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
}