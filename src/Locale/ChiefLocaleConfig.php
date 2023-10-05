<?php

namespace Thinktomorrow\Chief\Locale;

class ChiefLocaleConfig
{
    /**
     * Get the active locales. These are the locales that are available on the site.
     */
    public static function getSiteLocales(): array
    {
        if (is_null(config('chief.locales.site'))) {
            return static::getLocales();
        }

        return config('chief.locales.site', []);
    }

    /**
     * Get all the available locales for the admin
     */
    public static function getLocales(): array
    {
        return config('chief.locales.admin', []);
    }

    public static function getDefaultLocale(): string
    {
        $locales = static::getLocales();

        return (count($locales) > 0)
            ? reset($locales)
            : config('app.fallback_locale', 'nl');
    }
}
