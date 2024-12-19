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

        foreach ($sites as $locale => $site) {
            $chiefSites[] = new ChiefSite(
                $locale,
                $site['name'],
                $site['short_name'] ?? $site['name'],
                $site['url'],
                $site['active'] ?? true,
                $site['iso_code'] ?? $locale,
                $site['default_locale'] ?? null
            );
        }

        return new static(...$chiefSites);
    }

    public function find(string $locale): ?ChiefSite
    {
        foreach ($this->sites as $site) {
            if ($site->locale === $locale) {
                return $site;
            }
        }

        return null;
    }

    public function getLocales(): array
    {
        return array_map(fn (ChiefSite $site) => $site->locale, $this->sites);
    }

    public function getActiveSites(): array
    {
        return array_filter($this->sites, fn (ChiefSite $site) => $site->isActive);
    }

    public function getDefaultLocale(): string
    {
        return $this->sites[0]?->locale ?? config('app.fallback_locale', 'nl');
    }

    public static function locales(): array
    {
        return app(ChiefSites::class)->getLocales();
    }

    public static function activeSites(): array
    {
        return app(ChiefSites::class)->getActiveSites();
    }

    public static function defaultLocale(): string
    {
        return app(ChiefSites::class)->getDefaultLocale();
    }
}
