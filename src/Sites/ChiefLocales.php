<?php

namespace Thinktomorrow\Chief\Sites;

class ChiefLocales
{
    public static function locales(): array
    {
        static $locales;

        if ($locales) {
            return $locales;
        }

        return $locales = ChiefSites::fromConfig()->getLocales();
    }

    public static function localesBySites(array $siteIds): array
    {
        return ChiefSites::fromConfig()->filterByIds($siteIds)->getLocales();
    }

    public static function fallbackLocales(): array
    {
        $fallbackLocales = [];

        foreach (ChiefSites::all() as $site) {
            $fallbackLocales[$site->locale] = $site->fallbackLocale;
        }

        return $fallbackLocales;
    }

    public static function primaryLocale(): string
    {
        static $primaryFieldLocale;

        if ($primaryFieldLocale) {
            return $primaryFieldLocale;
        }

        return $primaryFieldLocale = ChiefSites::fromConfig()->getPrimaryLocale();
    }

    public static function localeGroups(array $locales, array $distinctLocales): array
    {
        $fallbackLocales = static::fallbackLocales();

        $groups = [];

        foreach ($locales as $locale) {

            // Locale is already set as root fallback or given locale is no longer present in the sites config
            if (isset($groups[$locale]) || ! array_key_exists($locale, $fallbackLocales)) {
                continue;
            }

            $fallbackLocale = $fallbackLocales[$locale];

            if (! in_array($fallbackLocale, $locales) || $fallbackLocale == $locale) {
                $groups[$locale] = [$locale];

                continue;
            }

            $rootFallback = $fallbackLocale ? self::resolveRootFallback($fallbackLocale, $fallbackLocales) : $locale;

            if (! isset($groups[$rootFallback])) {
                $groups[$rootFallback] = [$rootFallback];
            }
            $groups[$rootFallback][] = $locale;
        }

        // Now group the ones together that have a fallback don't have a distinct value

        return $groups;
    }

    private static function resolveRootFallback(?string $fallbackLocale, array $fallbackLocales): ?string
    {
        while ($fallbackLocale && isset($fallbackLocales[$fallbackLocale])) {
            $fallbackLocale = $fallbackLocales[$fallbackLocale];
        }

        return $fallbackLocale;
    }
}
