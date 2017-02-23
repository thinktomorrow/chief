<?php

namespace Chief\Locale;

trait TranslatableCommand
{
    /**
     * Update or create translatable fields for a translatable entity
     *
     * @param $translations
     * @param TranslatableContract $entity
     * @param array $keys pass the columns that need to be translated. these need to match the passed request keys
     */
    protected function saveTranslations($translations, TranslatableContract $entity, array $keys)
    {
        foreach ($entity->getAvailableLocales() as $available_locale)
        {
            // Remove the product translation if any already exists
            // Translation is also removed if all fields of a translation are left empty
            if (!isset($translations[$available_locale]) or !($translation = $translations[$available_locale]) or $this->isCompletelyEmpty($keys, $translation))
            {
                $entity->removeTranslation($available_locale);
                continue;
            }

            $this->updateTranslation($entity, $keys, $translation, $available_locale);
        }
    }

    /**
     * @param TranslatableContract $entity
     * @param array $keys
     * @param $translation
     * @param $available_locale
     */
    protected function updateTranslation(TranslatableContract $entity, array $keys, array $translation, $available_locale)
    {
        $attributes = [];

        foreach ($keys as $key)
        {
            if(isset($translation[$key]))
            {
                $attributes[$key] = $translation[$key];
            }
        }

        $entity->updateTranslation($available_locale, $attributes);
    }

    /**
     * Check if certain locale input submission is left empty
     *
     * @param array $keys
     * @param $translation
     * @return bool
     */
    protected function isCompletelyEmpty(array $keys, array $translation)
    {
        $is_completely_empty = true;

        foreach ($keys as $key)
        {
            if(!isset($translation[$key])) continue;

            if (trim($translation[$key]))
            {
                $is_completely_empty = false;
            }
        }

        return $is_completely_empty;
    }
}