<?php

namespace Thinktomorrow\Chief\Sites;

class ChiefSites
{
    /** @var ChiefSite[] */
    private array $sites;

    private function __construct(ChiefSite ...$sites)
    {
        $this->sites = $sites;
    }

    public static function fromArray(array $sites): self
    {
        $chiefSites = [];

        foreach ($sites as $site) {
            $chiefSites[] = ChiefSite::fromArray($site);
        }

        return new static(...$chiefSites);
    }

    //    public function findByLocale(string $locale): ?ChiefSite
    //    {
    //        foreach ($this->sites as $site) {
    //            if ($site->locale === $locale) {
    //                return $site;
    //            }
    //        }
    //
    //        return null;
    //    }

    public function getLocales(): array
    {
        return array_map(fn (ChiefSite $site) => $site->locale, $this->sites);
    }

    /**
     * Grouped locales by fallback logic. First locale is the fallback locale.
     */
    public function getGroupedLocales(): array
    {
        $grouped = [];

        foreach ($this->sites as $site) {

            if ($site->fallbackLocale) {
                if (! isset($grouped[$site->fallbackLocale])) {
                    $grouped[$site->fallbackLocale] = [];
                }

                $grouped[$site->fallbackLocale][] = $site->locale;
            } elseif (! isset($grouped[$site->locale])) {
                $grouped[$site->locale] = [];
            }
        }

        return $grouped;
    }

    public function onlyActive(): self
    {
        return new static(...array_filter($this->sites, fn (ChiefSite $site) => $site->isActive));
    }

    public function getPrimaryLocale(): string
    {
        return $this->sites[0]?->locale;
    }

    /**
     * ['nl', 'be']
     * ['fr']
     * @return array
     */
    public static function locales(): array
    {
        // Get all locales... but those with fallback logic are grouped together...
        static $locales;

        if ($locales) {
            return $locales;
        }

        return $locales = static::fromArray(config('chief.sites'))->getGroupedLocales();
    }

    public static function primaryLocale(): string
    {
        static $primaryLocale;

        if ($primaryLocale) {
            return $primaryLocale;
        }

        return $primaryLocale = static::fromArray(config('chief.sites'))->getPrimaryLocale();
    }

    public function toArray(): array
    {
        return array_map(fn (ChiefSite $site) => $site->toArray(), $this->sites);
    }

    //
    //    public static function activeSites(): array
    //    {
    //        return app(ChiefSites::class)->getActiveSites();
    //    }
    //
}
