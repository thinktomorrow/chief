<?php

namespace Thinktomorrow\Chief\Resource\Locale;

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
}
